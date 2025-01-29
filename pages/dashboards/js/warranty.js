let grnDataArray = []
let grnItemArray = []
let invDataArray = []
let invItemArray = []
let ginDataArray = []
let ginItemArray = []
let mainArray = []

async function fetchData (url, arrayName) {
  try {
    const response = await fetch(url)
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    const data = await response.json()
    if (arrayName === 'grnDataArray') {
      grnDataArray = data
    } else if (arrayName === 'grnItemArray') {
      grnItemArray = data
    } else if (arrayName === 'invDataArray') {
      invDataArray = data
    } else if (arrayName === 'invItemArray') {
      invItemArray = data
    } else if (arrayName === 'ginDataArray') {
      ginDataArray = data
    } else if (arrayName === 'ginItemArray') {
      ginItemArray = data
    }
    mergeGrnData()
    mergeInvData()
    mergeGinData()
  } catch (error) {
    console.error(`Error fetching data from ${url}:`, error)
  }
}

fetchData('../../functions/fetchGrnData.php', 'grnDataArray')
fetchData('../../functions/fetchGrnItem.php', 'grnItemArray')
fetchData('../../functions/fetchInvoice.php', 'invDataArray')
fetchData('../../functions/fetchInvoiceItems.php', 'invItemArray')
fetchData('../../functions/fetchGin.php', 'ginDataArray')
fetchData('../../functions/fetchGinItems.php', 'ginItemArray')

function mergeGinData () {
  tempGinArray = []
  if (ginDataArray.length && ginItemArray.length) {
    const combinedArray = ginItemArray.map(item => {
      const ginData = ginDataArray.find(gin => gin.id === item.gin_id)
      return { ...item, ...ginData }
    })
    tempGinArray = combinedArray.flatMap(item => {
      const serials = item.serial
        ? item.serial.split(',').map(sn => sn.trim())
        : ['']
      return serials.map(sn => ({
        invNo: 'GIN000' + item.id,
        customer: item.customer,
        salesRep: '',
        item: item.itemCode,
        sn: sn,
        value: '',
        createdAt: item.created_at,
        warrantyDate: '',
        description:
          'Object : ' + item.object + ', End-Customer : ' + item.end_customer
      }))
    })
    mainArray.push(...tempGinArray)
  }
}

function mergeInvData () {
  tempInvArray = []
  if (invDataArray.length && invItemArray.length) {
    const combinedArray = invItemArray.map(item => {
      const invData = invDataArray.find(inv => inv.id === item.inv_no)
      return { ...item, ...invData }
    })
    tempInvArray = combinedArray.flatMap(item => {
      const serials = item.serials
        ? item.serials.split(',').map(sn => sn.trim())
        : ['']
      return serials.map(sn => ({
        invNo: item.inv,
        customer: item.customer,
        salesRep: item.rep,
        item: item.item_code,
        sn: sn,
        value: item.unit_price,
        createdAt: item.created_at,
        warrantyDate: item.warranty,
        description: item.description
      }))
    })
    mainArray.push(...tempInvArray)
  }
}

function mergeGrnData () {
  tempGrnArray = []
  if (grnDataArray.length && grnItemArray.length) {
    const combinedArray = grnItemArray.map(item => {
      const grnData = grnDataArray.find(grn => grn.id === item.grn_id)
      return { ...item, ...grnData }
    })
    tempGrnArray = combinedArray.flatMap(item => {
      const serials = item.serial
        ? item.serial.split(',').map(sn => sn.trim())
        : ['']
      return serials.map(sn => ({
        invNo: 'GRN000' + item.id,
        customer: item.supplier,
        salesRep: '',
        item: item.itemCode,
        sn: sn,
        value: '',
        createdAt: item.created_at,
        warrantyDate: '',
        description:
          'Object  :  ' + item.object + ', ' + 'Method  :  ' + item.method
      }))
    })
    mainArray.push(...tempGrnArray)
    //   console.log('mainArray', mainArray);
  }
}

let hot // Declare globally accessible Handsontable instance

document.addEventListener('DOMContentLoaded', function () {
  // Fetch all data and wait for promises to resolve
  Promise.all([
    fetchData('../../functions/fetchGrnData.php', 'grnDataArray'),
    fetchData('../../functions/fetchGrnItem.php', 'grnItemArray'),
    fetchData('../../functions/fetchInvoice.php', 'invDataArray'),
    fetchData('../../functions/fetchInvoiceItems.php', 'invItemArray'),
    fetchData('../../functions/fetchGin.php', 'ginDataArray'),
    fetchData('../../functions/fetchGinItems.php', 'ginItemArray')
  ])
    .then(() => {
      //console.log('mainArray', mainArray); // Log mainArray to verify data

      const container = document.getElementById('hot')

      // Calculate dynamic width and height
      const tableHeight = window.innerHeight * 0.8 // 80% of screen height
      const tableWidth = window.innerWidth * 0.9 // 90% of screen width
      const columnWidths = [80, 150, 120, 100, 130, 80, 150, 150, 200] // Adjust widths as needed
      let lastClickTime = 0
      let lastClickCell = null

      hot = new Handsontable(container, {
        data: mainArray, // Bind mainArray as data
        height: tableHeight, // Dynamic height
        width: tableWidth, // Dynamic width
        stretchH: 'all', // Stretch columns to fit width
        colHeaders: [
          'Invoice No',
          'Customer',
          'Sales Rep',
          'Item',
          'Serial Number',
          'Value',
          'Created At',
          'Warranty Date',
          'Description'
        ], // Define column headers
        columns: [
          { data: 'invNo', type: 'text', readOnly: true },
          { data: 'customer', type: 'text', readOnly: true },
          { data: 'salesRep', type: 'text', readOnly: true },
          { data: 'item', type: 'text', readOnly: true },
          { data: 'sn', type: 'text', readOnly: true },
          {
            data: 'value',
            type: 'numeric',
            numericFormat: { pattern: '0,0.00' },
            readOnly: true
          },
          {
            data: 'createdAt',
            type: 'date',
            dateFormat: 'YYYY-MM-DD HH:mm:ss',
            readOnly: true
          },
          {
            data: 'warrantyDate',
            type: 'text',
            dateFormat: 'YYYY-MM-DD',
            readOnly: true
          },
          { data: 'description', type: 'text', readOnly: true }
        ], // Bind data fields to columns
        colWidths: columnWidths, // Apply fixed column widths
        width: '100%', // Set width to 100% of container
        rowHeaders: false, // Enable row headers
        filters: true, // Enable column filters
        dropdownMenu: true, // Enable dropdown menu for additional options
        columnSorting: true, // Enable column sorting
        contextMenu: {
          items: {
            refresh_table: {
              name: 'Refresh Table',
              callback: function () {
                // Define the refresh logic here
                refreshTable()
              }
            },
            separator: Handsontable.plugins.ContextMenu.SEPARATOR, // Add a separator
            ...Handsontable.plugins.ContextMenu.defaultItems // Include default items
          }
        },
        manualColumnResize: true, // Allow resizing of columns
        licenseKey: 'non-commercial-and-evaluation', // Free license
        afterChange: function (changes, source) {
          if (source === 'loadData') {
            return // Skip logic on initial data load
          }
          console.log('Data changed:', changes) // Log changes for debugging
        },
        afterOnCellMouseDown: function (event, coords) {
          const currentTime = new Date().getTime()

          // Check if this click is within a double-click time threshold and on the same cell
          if (
            lastClickCell &&
            coords.row === lastClickCell.row &&
            coords.col === lastClickCell.col &&
            currentTime - lastClickTime < 300 // Adjust threshold as needed
          ) {
            // Double-click detected
            console.log(
              'Double-clicked value: ' +
                hot.getDataAtCell(coords.row, coords.col)
            )
            moreDetails(hot.getDataAtCell(coords.row, coords.col))
          }

          // Update the last click information
          lastClickTime = currentTime
          lastClickCell = { row: coords.row, col: coords.col }
        }
      })

      // Function to filter the table
      function filterTable (query) {
        const searchQuery = query.toLowerCase()
        const filteredData = mainArray.filter(row =>
          Object.values(row).some(cell =>
            cell.toString().toLowerCase().includes(searchQuery)
          )
        )

        hot.loadData(filteredData) // Update table data with filtered data
      }

      // Event listener for search input
      document.getElementById('search').addEventListener('input', function () {
        const query = this.value
        console.log('Search query:', query) // Debug: Check the search query
        filterTable(query)
      })
      document.getElementById('search2').addEventListener('input', function () {
        const query = this.value
        console.log('Search query:', query) // Debug: Check the search query
        filterTable(query)
      })

      // Modal functionality
      const modal = document.getElementById('myModal')
      const openModalBtn = document.getElementById('openModalBtn')
      const closeModalBtn = document.getElementById('closeModalBtn')
      const hotContainer = document.getElementById('hot-container')

      openModalBtn.addEventListener('click', () => {
        modal.classList.remove('hidden')
        // hotContainer.classList.add('blur'); // Apply blur effect
      })

      closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden')
        // hotContainer.classList.remove('blur'); // Remove blur effect
      })

      // Mobile menu functionality
      const mobileMenuButton = document.getElementById('mobileMenuButton')
      const mobileMenu = document.getElementById('mobileMenu')

      mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden')
      })
    })
    .catch(error => {
      console.error('Error initializing Handsontable:', error)
    })
})
// Function to fetch new data and refresh the table
async function refreshTable () {
  location.reload()
}

function moreDetails (value) {
  const modalBody = document.getElementById('modalBody')
  modalBody.innerHTML = `<p>${value}</p>`
  openModal()
}

// // Event listener for refresh button
// document.getElementById('refreshButton').addEventListener('click', refreshData);
