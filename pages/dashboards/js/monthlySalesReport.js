const invoiceData = []
let reportData = []
let fetchedDate = ['test1']

const fetchProductDetails = async () => {
  const response = await fetch('../../functions/fetchItems.php')
  const data = await response.json()
  return data
}

console.log('fetchProductDetails:',fetchProductDetails())

function fetchMasterInvoice () {

  const loadingScreen = document.getElementById('loadingScreen')
  loadingScreen.style.display = 'flex'

  fetch('../../functions/fetchMasterInv.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Error Fetching master invoices')
      }
      return response.json()
    })
    .then(data => {
      const filteredInvoices = data.filter(
        invoice => !invoice.gin && !invoice.grn
      )

      

      // Fetch product details
      return Promise.all([filteredInvoices, fetchProductDetails()])
    })
    .then(([filteredInvoices, productsData]) => {
      // console.log('fetchProducts:', productsData);

      const products = productsData.map(product => {
        return {
          itemCode: product.item_code,
          brand: product.brand,
          category: product.category
        }
      })
      console.log('products:', products);

      filteredInvoices.forEach(x => {
        const product = products.find(p => p.itemCode.trim() === x.itemCode.trim())
        if (product) {
          fetchedDate.push(x.date) // Consider pushing just x.date
          invoiceData.push({
            date: x.date,
            invoice: x.inv,
            brand : product.brand,
            customer: x.customer,
            category: product.category,
            value: parseFloat(x.value),
            vat: parseFloat(x.vat),
            other: parseFloat(x.others || 0),
            total:
              parseFloat(x.value) +
              parseFloat(x.vat) +
              parseFloat(x.others || 0)
          })
        //   console.log('product:', product.itemCode,'category',product.category)
        } else {
          fetchedDate.push(x.date) // Consider pushing just x.date
          invoiceData.push({
            date: x.date,
            invoice: x.inv,
            customer: x.customer,
            category: '',
            brand: x.brand,
            value: parseFloat(x.value),
            vat: parseFloat(x.vat),
            other: parseFloat(x.others || 0),
            total:
              parseFloat(x.value) +
              parseFloat(x.vat) +
              parseFloat(x.others || 0)
          })
          console.log('product not found for itemCode:', x.itemCode)
        }
      })



      setTimeout(() => {

        displayInvoices(invoiceData)
        populateFilters()
        populateCusData(filteredInvoices)
        populateCusGpData(filteredInvoices)
        populateRepSalesData(filteredInvoices)
        populateRepGpData(filteredInvoices)
        populatepProSaleData(filteredInvoices)
        populatepProGpData(filteredInvoices)
        populateOverView(filteredInvoices)
        displayChart(filteredInvoices)
    
        loadingScreen.style.display = 'none'
      }, 1000)




    })
    .catch(error => {
      console.error('Error fetching master invoice', error)
    })
}




function displayChart (response) {
  const currentDate = new Date()
  const currentMonth = currentDate.getMonth() + 1
  const currentYear = currentDate.getFullYear()

  // Filter data for the current month and year
  const reportData = response.filter(x => {
    const [year, month] = x.date.split('-')
    return parseInt(year) === currentYear
  })

  function topProducts (reportData) {
    const totalValuesByProduct = {}

    // Group data by product and calculate total values
    reportData.forEach(invoice => {
      const productName = invoice.brand
      const productValue = parseFloat(invoice.value)

      if (!totalValuesByProduct[productName]) {
        totalValuesByProduct[productName] = 0
      }

      totalValuesByProduct[productName] += productValue
    })

    // Sort products by total value in descending order
    const sortedProducts = Object.entries(totalValuesByProduct)
      .sort((a, b) => b[1] - a[1])
      .slice(0, 2) // Get top 2 products

    // Calculate total value for percentage calculations
    const totalValue = Object.values(totalValuesByProduct).reduce(
      (acc, curr) => acc + curr,
      0
    )

    // Update HTML fields for top 2 products
    if (sortedProducts.length > 0) {
      const [top1Name, top1Value] = sortedProducts[0]
      const top1Percentage = ((top1Value / totalValue) * 100).toFixed(2)

      document.getElementById('top1Category').textContent = top1Name
      document.getElementById(
        'top1CategoryValue'
      ).textContent = `${top1Percentage}%`
      document.getElementById(
        'top1CategoryProgress'
      ).style.width = `${top1Percentage}%`
    }

    if (sortedProducts.length > 1) {
      const [top2Name, top2Value] = sortedProducts[1]
      const top2Percentage = ((top2Value / totalValue) * 100).toFixed(2)

      document.getElementById('top2Category').textContent = top2Name
      document.getElementById(
        'top2CategoryValue'
      ).textContent = `${top2Percentage}%`
      document.getElementById(
        'top2CategoryProgress'
      ).style.width = `${top2Percentage}%`
    }

    // Handle cases where there are fewer than 2 products
    if (sortedProducts.length < 2) {
      if (sortedProducts.length === 0) {
        document.getElementById('top1Category').textContent = 'N/A'
        document.getElementById('top1CategoryValue').textContent = '0%'
        document.getElementById('top1CategoryProgress').style.width = '0%'
      }
      document.getElementById('top2Category').textContent = 'N/A'
      document.getElementById('top2CategoryValue').textContent = '0%'
      document.getElementById('top2CategoryProgress').style.width = '0%'
    }
  }

  function topCustomers (reportData) {
    //console.log('reportData:', reportData);

    const totalValuesByCustomer = {}

    // Group data by customer and calculate total values
    reportData.forEach(invoice => {
      const customerName = invoice.customer
      const invoiceValue = parseFloat(invoice.value)

      if (!totalValuesByCustomer[customerName]) {
        totalValuesByCustomer[customerName] = 0
      }

      totalValuesByCustomer[customerName] += invoiceValue
    })

    // Sort customers by total value in descending order
    const sortedCustomers = Object.entries(totalValuesByCustomer)
      .sort((a, b) => b[1] - a[1])
      .slice(0, 2) // Get top 2 customers

    //console.log('Top customers:', sortedCustomers);

    // Get the table element
    const customerTable = document.getElementById('customerTable')

    if (customerTable) {
      // Clear existing rows
      customerTable.innerHTML = ''

      // Populate table with top customers
      sortedCustomers.forEach(([customer, totalValue], index) => {
        const row = document.createElement('tr')
        row.className = 'manager-row' // Add a consistent row class
        if (index === 0) {
          row.style.borderBottom = '1px solid #ccc'
        }

        // Customer name cell
        const nameCell = document.createElement('td')
        nameCell.className = 'bg-transparent p-3 border-end-0'
        nameCell.textContent = customer

        // Total value cell
        const valueCell = document.createElement('th')
        valueCell.className = 'text-end p-3 bg-transparent'
        valueCell.textContent = totalValue.toLocaleString() // Format as a string with commas

        // Append cells to row
        row.appendChild(nameCell)
        row.appendChild(valueCell)

        // Append row to table
        customerTable.appendChild(row)
      })

      // Handle cases where there are fewer than 2 customers
      const missingRows = 2 - sortedCustomers.length
      for (let i = 0; i < missingRows; i++) {
        const row = document.createElement('tr')

        const nameCell = document.createElement('td')
        nameCell.className = 'bg-transparent p-3 border-end-0'
        nameCell.textContent = 'N/A'

        const valueCell = document.createElement('th')
        valueCell.className = 'text-end p-3 bg-transparent'
        valueCell.textContent = '0'

        row.appendChild(nameCell)
        row.appendChild(valueCell)

        customerTable.appendChild(row)
      }
    }
  }

  function topManagers (reportData) {
    //console.log('topManagers',reportData);
    const totalValuesByRep = {}

    // Calculate total sales for each manager
    reportData.forEach(invoice => {
      const repName = invoice.rep
      const invoiceValue = parseFloat(invoice.value)

      if (!totalValuesByRep[repName]) {
        totalValuesByRep[repName] = 0
      }

      totalValuesByRep[repName] += invoiceValue
    })

    // Sort and get top 2 performers
    const sortedSalesPerformers = Object.entries(totalValuesByRep)
      .sort((a, b) => b[1] - a[1])
      .slice(0, 2)

    //console.log('Top 2 sales performers:', sortedSalesPerformers);

    // Get the table element
    const managerTable = document.getElementById('managerTable')

    if (managerTable) {
      // Clear existing rows
      managerTable.innerHTML = ''

      // Dynamically create rows for top performers
      sortedSalesPerformers.forEach(([manager, sales], index) => {
        const row = document.createElement('tr')
        row.className = 'manager-row' // Add a consistent row class

        // Add a bottom border for the first row
        if (index === 0) {
          row.style.borderBottom = '1px solid #ccc'
        }

        // Name cell
        const nameCell = document.createElement('td')
        nameCell.className = 'bg-transparent p-3 border-end-0'
        nameCell.textContent = manager

        // Sales value cell
        const valueCell = document.createElement('th')
        valueCell.className = 'text-end p-3 bg-transparent'
        valueCell.textContent = sales

        // Append cells to row
        row.appendChild(nameCell)
        row.appendChild(valueCell)

        // Append row to table
        managerTable.appendChild(row)
      })

      // Handle cases where there are fewer than 2 performers
      const missingRows = 2 - sortedSalesPerformers.length
      for (let i = 0; i < missingRows; i++) {
        const row = document.createElement('tr')

        const nameCell = document.createElement('td')
        nameCell.className = 'bg-transparent p-3 border-end-0'
        nameCell.textContent = 'N/A'

        const valueCell = document.createElement('th')
        valueCell.className = 'text-end p-3 bg-transparent'
        valueCell.textContent = '0'

        row.appendChild(nameCell)
        row.appendChild(valueCell)

        managerTable.appendChild(row)
      }
    }
  }
  topManagers(reportData)
  topCustomers(reportData)
  topProducts(reportData)
}

function populateFilters () {
  const customers = [...new Set(invoiceData.map(item => item.customer))]
  const categories = [...new Set(invoiceData.map(item => item.category))]

  const customerSelect = $('#customerFilter')
  const categorySelect = $('#categoryFilter')

  // Check if Select2 is initialized before calling destroy
  if (customerSelect.hasClass('select2-hidden-accessible')) {
    customerSelect.select2('destroy')
  }
  if (categorySelect.hasClass('select2-hidden-accessible')) {
    categorySelect.select2('destroy')
  }

  customerSelect.empty().append('<option value="">Select Customer</option>')
  categorySelect.empty().append('<option value="">Select Category</option>')

  customers.forEach(customer => {
    customerSelect.append(new Option(customer, customer))
  })
  categories.forEach(category => {
    categorySelect.append(new Option(category, category))
  })

  customerSelect.select2({
    placeholder: 'Select Customer',
    allowClear: true,
    width: '100%'
  })
  categorySelect.select2({
    placeholder: 'Select Category',
    allowClear: true,
    width: '100%'
  })
}

// Initialize
function initializeTable () {
  //populateFilters();
  fetchMasterInvoice()
  displayInvoices(invoiceData)
  //   updateSummaryStats();
}

// Update summary statistics
/* function updateSummaryStats() {
     document.getElementById('totalInvoices').textContent = invoiceData.length;
     document.getElementById('totalValue').textContent = '$' + invoiceData.reduce((sum, inv) => sum + inv.total, 0).toFixed(2);
     document.getElementById('totalCustomers').textContent = new Set(invoiceData.map(inv => inv.customer)).size;
     document.getElementById('totalCategories').textContent = new Set(invoiceData.map(inv => inv.category)).size;
 }*/

// Display invoices
function displayInvoices (data) {
  const tbody = document.getElementById('invoiceTableBody')
  tbody.innerHTML = ''

  // Create a number formatter with commas for thousands and two decimals
  const formatter = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })

  data.forEach(invoice => {
    console.log('invoice', invoice);
    const row = document.createElement('tr')
    row.innerHTML = `
            <td>${formatDate(invoice.date)}</td>
            <td>${invoice.invoice}</td>
            <td>${invoice.customer}</td>
            <td>${invoice.brand}</td>
            <td>${formatter.format(invoice.value)}</td>
            <td>${formatter.format(invoice.vat)}</td>
            <td>${formatter.format(invoice.other)}</td>
            <td><strong>${formatter.format(invoice.total)}</strong></td>
        `
    tbody.appendChild(row)
  })
}

// Apply filters
function applyFilters () {
  const month = document.getElementById('monthFilter').value
  const customer = document.getElementById('customerFilter').value
  const category = document.getElementById('categoryFilter').value

  let filteredData = invoiceData

  // Filter by month (extract `mm-yyyy` from `yyyy-mm-dd` format)
  if (month) {
    const [selectedMonth, selectedYear] = month.split('-')
    filteredData = filteredData.filter(item => {
      const [year, itemMonth] = item.date.split('-')
      return itemMonth === selectedMonth && year === selectedYear
    })
  }
  // Event listeners for filters
  $('#customerFilter').on('change', applyFilters)
  $('#categoryFilter').on('change', applyFilters)
  $('#monthFilter').on('changeDate', applyFilters) // For month filter

  // Filter by customer
  if (customer) {
    filteredData = filteredData.filter(item => item.customer === customer)
  }
  // Filter by category
  if (category) {
    filteredData = filteredData.filter(item => item.category === category)
  }

  displayInvoices(filteredData)
}

// Sort table
function sortTable (columnIndex) {
  const table = document.getElementById('invoiceTable')
  const tbody = table.querySelector('tbody')
  const rows = Array.from(tbody.querySelectorAll('tr'))

  const sortDirection = table.getAttribute('data-sort-dir') === 'asc' ? -1 : 1
  table.setAttribute('data-sort-dir', sortDirection === 1 ? 'asc' : 'desc')

  rows.sort((a, b) => {
    const aValue = a.cells[columnIndex].textContent
    const bValue = b.cells[columnIndex].textContent

    // Handle numeric values (including currency)
    if (aValue.includes('$')) {
      return (
        sortDirection *
        (parseFloat(aValue.replace('$', '')) -
          parseFloat(bValue.replace('$', '')))
      )
    }

    return sortDirection * aValue.localeCompare(bValue)
  })

  rows.forEach(row => tbody.appendChild(row))
}

// Format date
function formatDate (dateStr) {
  const date = new Date(dateStr)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

//------------cusSalesReport-------------------------cusSalesReport-------------------------cusSalesReport-------------------------cusSalesReport-------------------------cusSalesReport-------------
let hot // Define Handsontable instance globally

// Tab change handler
function populateCusData (data) {
  //console.log('data', data);

  document.getElementById('cus-tab').addEventListener('click', function () {
    const container = document.getElementById('salesTable')

    document.getElementById('cusFilterBtn').addEventListener('click', () => {
      const from = document.getElementById('cusFromMonth').value
      const to = document.getElementById('cusToMonth').value

      // Filter data by the selected date range
      const filteredData = data.filter(item => {
        const itemDate = new Date(item.date)
        const fromDate = new Date(from)
        const toDate = new Date(to)
        return itemDate >= fromDate && itemDate <= toDate
      })

      // Group and aggregate the filtered data
      const groupedData = groupDataByCustomerAndRep(filteredData)
      displayData(groupedData)
    })

    // Initially show current year data
    const currentYear = new Date().getFullYear()
    const currentYearData = data.filter(
      item => new Date(item.date).getFullYear() === currentYear
    )

    // Group and aggregate the current year data
    const groupedData = groupDataByCustomerAndRep(currentYearData)
    displayData(groupedData)
  })
}

// Function to group data by customer and rep, and aggregate by month
function groupDataByCustomerAndRep (data) {
  function normalizeRepName (rep) {
    const repMap = {
      A: 'Anjana',
      AD: 'amal',
      AR: 'Arkam',
      AS: 'Shaheer',
      SA: 'sameer'
    }
    return repMap[rep] || rep
  }
  const groupedData = {}
  data.forEach(item => {
    const key = `${item.customer}_${item.rep || 'N/A'}`

    const rep = normalizeRepName(item.rep || 'N/A')

    if (!groupedData[key]) {
      groupedData[key] = {
        customer: item.customer,
        rep: rep,
        jan: 0,
        feb: 0,
        mar: 0,
        apr: 0,
        may: 0,
        jun: 0,
        jul: 0,
        aug: 0,
        sep: 0,
        oct: 0,
        nov: 0,
        dec: 0,
        total: 0 // Initialize total to 0
      }
    }

    // Add the item's value to the respective month
    const month = new Date(item.date).getMonth()
    const monthKeys = [
      'jan',
      'feb',
      'mar',
      'apr',
      'may',
      'jun',
      'jul',
      'aug',
      'sep',
      'oct',
      'nov',
      'dec'
    ]
    groupedData[key][monthKeys[month]] += parseFloat(item.value || 0)
  })

  // Calculate totals for each customer/rep combination
  Object.values(groupedData).forEach(row => {
    row.total = [
      'jan',
      'feb',
      'mar',
      'apr',
      'may',
      'jun',
      'jul',
      'aug',
      'sep',
      'oct',
      'nov',
      'dec'
    ].reduce((sum, month) => sum + parseFloat(row[month] || 0), 0)
  })

  return groupedData
}

// Function to display the grouped data in Handsontable
function displayData (groupedData) {
  const tableData = Object.values(groupedData).sort((a, b) => b.total - a.total)

  // Destroy the previous Handsontable instance if it exists
  if (hot) {
    hot.destroy()
  }

  // Initialize Handsontable
  hot = new Handsontable(document.getElementById('salesTable'), {
    data: tableData,
    columns: [
      { data: 'customer', type: 'text', readOnly: true, filter: true },
      {
        data: 'rep',
        type: 'text',
        readOnly: true,
        filter: true,
        renderer: function (
          instance,
          td,
          row,
          col,
          prop,
          value,
          cellProperties
        ) {
          Handsontable.renderers.TextRenderer.apply(this, arguments)
          if (value) {
            td.textContent =
              value.charAt(0).toUpperCase() + value.slice(1).toLowerCase()
          }
        }
      },
      {
        data: 'jan',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'feb',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'mar',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'apr',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'may',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'jun',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'jul',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'aug',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'sep',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'oct',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'nov',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'dec',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'total',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' },
        renderer: totalRenderer
      }
    ],
    colHeaders: [
      'Customer Name',
      'Rep',
      'Jan',
      'Feb',
      'Mar',
      'Apr',
      'May',
      'Jun',
      'Jul',
      'Aug',
      'Sep',
      'Oct',
      'Nov',
      'Dec',
      'Total'
    ],
    width: '100%',
    height: 450, // Set the height for scrollable content
    rowHeaders: false,
    stretchH: 'all',
    licenseKey: 'non-commercial-and-evaluation',
    dropdownMenu: ['filter_by_value', 'filter_action_bar'], // Enable filtering
    filters: true,
    contextMenu: {
      items: {
        view_rep_details: {
          name: 'View Rep Details',
          callback: function () {
            const rep = hot.getDataAtCell(this.getSelected()[0][0], 1) // Rep column
            alert(`Viewing details for Rep: ${rep}`)
          }
        },
        view_full_details: {
          name: 'View Full Details',
          callback: function () {
            const customer = hot.getDataAtCell(this.getSelected()[0][0], 0) // Customer column
            alert(`Viewing full details for Customer: ${customer}`)
          }
        },
        view_gp_report: {
          name: 'View GP Report',
          callback: function () {
            const customer = hot.getDataAtCell(this.getSelected()[0][0], 0) // Customer column
            alert(`Viewing GP Report for Customer: ${customer}`)
          }
        },
        separator: Handsontable.plugins.ContextMenu.SEPARATOR,
        remove_row: {
          name: 'Remove this row'
        }
      }
    }
  })
}

// Custom renderer for the total column to style it
function totalRenderer (instance, td, row, col, prop, value, cellProperties) {
  Handsontable.renderers.NumericRenderer.apply(this, arguments)
  td.style.fontWeight = 'bold'
  td.style.backgroundColor = '#f0f0f0'
}

//------------cusSalesGpReport-------------------------cusSalesGpReport-------------------------cusSalesGpReport-------------------------cusSalesReport-------------------------cusSalesReport-------------

let cusGpHot

// Tab change handler
function populateCusGpData (data) {
  // //console.log('data', data);

  document.getElementById('cus-gp-tab').addEventListener('click', function () {
    // //console.log('clicked')
    const container = document.getElementById('gpTable')

    // Initially show current year data immediately upon tab click
    const currentYear = new Date().getFullYear()
    const currentYearData = data.filter(
      item => new Date(item.date).getFullYear() === currentYear
    )

    // Group and aggregate the current year data
    const groupedData = groupDataByCustomerAndRepGp(currentYearData)

    // Display data in the table
    displayGpData(groupedData)

    // Filter data on button click
    document.getElementById('cusGpFilterBtn').addEventListener('click', () => {
      const from = document.getElementById('cusGpFromMonth').value
      const to = document.getElementById('cusGpToMonth').value
      //console.log('Filter button clicked!');

      // Filter data by the selected date range
      const filteredData = data.filter(item => {
        const itemDate = new Date(item.date)
        const fromDate = new Date(from)
        const toDate = new Date(to)
        return itemDate >= fromDate && itemDate <= toDate
      })

      // Group and aggregate the filtered data
      const groupedData = groupDataByCustomerAndRepGp(filteredData)
      displayGpData(groupedData)
    })
  })
}

// Function to group data by customer and rep, and aggregate GP by month
function groupDataByCustomerAndRepGp (data) {
  const groupedData = {}
  data.forEach(item => {
    const key = `${item.customer}_${item.rep || 'N/A'}`

    if (!groupedData[key]) {
      groupedData[key] = {
        customer: item.customer,
        rep: item.rep || 'N/A',
        gp_jan: 0,
        gp_feb: 0,
        gp_mar: 0,
        gp_apr: 0,
        gp_may: 0,
        gp_jun: 0,
        gp_jul: 0,
        gp_aug: 0,
        gp_sep: 0,
        gp_oct: 0,
        gp_nov: 0,
        gp_dec: 0,
        total_gp: 0 // Initialize GP total to 0
      }
    }

    const month = new Date(item.date).getMonth()
    const gpMonthKeys = [
      'gp_jan',
      'gp_feb',
      'gp_mar',
      'gp_apr',
      'gp_may',
      'gp_jun',
      'gp_jul',
      'gp_aug',
      'gp_sep',
      'gp_oct',
      'gp_nov',
      'gp_dec'
    ]

    // Add GP to the respective month
    if (item.gp !== undefined) {
      const gpKey = gpMonthKeys[month]
      groupedData[key][gpKey] += parseFloat(item.gp || 0)
    }
  })

  // Calculate the total GP for each customer/rep combination
  Object.values(groupedData).forEach(row => {
    row.total_gp = [
      'gp_jan',
      'gp_feb',
      'gp_mar',
      'gp_apr',
      'gp_may',
      'gp_jun',
      'gp_jul',
      'gp_aug',
      'gp_sep',
      'gp_oct',
      'gp_nov',
      'gp_dec'
    ].reduce((sum, month) => {
      return sum + (row[month] || 0)
    }, 0)
  })

  return groupedData
}

// Function to display the grouped data in Handsontable
function displayGpData (groupedData) {
  const tableData = Object.values(groupedData)
    .filter(row => {
      return (
        row.gp_jan > 0 ||
        row.gp_feb > 0 ||
        row.gp_mar > 0 ||
        row.gp_apr > 0 ||
        row.gp_may > 0 ||
        row.gp_jun > 0 ||
        row.gp_jul > 0 ||
        row.gp_aug > 0 ||
        row.gp_sep > 0 ||
        row.gp_oct > 0 ||
        row.gp_nov > 0 ||
        row.gp_dec > 0
      )
    })
    .sort((a, b) => b.total_gp - a.total_gp)
  // Initialize Handsontable
  cusGpHot = new Handsontable(document.getElementById('gpTable'), {
    data: tableData,
    columns: [
      { data: 'customer', type: 'text', readOnly: true, filter: true },
      { data: 'rep', type: 'text', readOnly: true, filter: true },
      {
        data: 'gp_jan',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_feb',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_mar',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_apr',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_may',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_jun',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_jul',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_aug',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_sep',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_oct',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_nov',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'gp_dec',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' }
      },
      {
        data: 'total_gp',
        type: 'numeric',
        readOnly: true,
        numericFormat: { pattern: '0,0.00' },
        renderer: gpTotalRenderer
      }
    ],
    colHeaders: [
      'Customer Name',
      'Rep',
      'Jan GP',
      'Feb GP',
      'Mar GP',
      'Apr GP',
      'May GP',
      'Jun GP',
      'Jul GP',
      'Aug GP',
      'Sep GP',
      'Oct GP',
      'Nov GP',
      'Dec GP',
      'Total GP'
    ],
    width: '100%',
    height: 450,
    rowHeaders: false,
    stretchH: 'all',
    licenseKey: 'non-commercial-and-evaluation',
    dropdownMenu: ['filter_by_value', 'filter_action_bar'],
    filters: true,
    contextMenu: {
      items: {
        view_rep_details: {
          name: 'View Rep Details',
          callback: function () {
            const rep = hot.getDataAtCell(this.getSelected()[0][0], 1) // Rep column
            alert(`Viewing details for Rep: ${rep}`)
          }
        },
        view_full_details: {
          name: 'View Full Details',
          callback: function () {
            const customer = hot.getDataAtCell(this.getSelected()[0][0], 0) // Customer column
            alert(`Viewing full details for Customer: ${customer}`)
          }
        },
        view_gp_report: {
          name: 'View GP Report',
          callback: function () {
            const customer = hot.getDataAtCell(this.getSelected()[0][0], 0) // Customer column
            alert(`Viewing GP Report for Customer: ${customer}`)
          }
        },
        separator: Handsontable.plugins.ContextMenu.SEPARATOR,
        remove_row: {
          name: 'Remove this row'
        }
      }
    }
  })
}

// Custom renderer for the GP Total column to handle 'N/A' properly
function gpTotalRenderer (instance, td, row, col, prop, value, cellProperties) {
  if (value === 'N/A') {
    td.innerText = 'N/A'
    td.style.fontStyle = 'italic'
    td.style.color = 'black'
  } else {
    Handsontable.renderers.NumericRenderer.apply(this, arguments)
    td.style.fontWeight = 'bold'
    td.style.backgroundColor = '#f0f0f0'
  }
  td.style.color = 'black'
}

//------------repSalesSumReport-------------------------repSalesSumReport-------------------------repSalesSumReport-------------------------repSalesReport-------------------------cusSalesReport-------------
let repSalesHot

// Tab change handler
function populateRepSalesData (data) {
  document
    .getElementById('rep-sale-tab')
    .addEventListener('click', function () {
      const container = document.getElementById('repSalesTable')
      const currentYear = new Date().getFullYear()
      const currentYearData = data.filter(
        item => new Date(item.date).getFullYear() === currentYear
      )

      // Group and aggregate the current year data
      const groupedData = groupDataByRepSales(currentYearData)

      // Display data in the table
      displayRepSaleData(groupedData)
      repSaleChart(groupedData)

      // Filter data on button click
      document.getElementById('repSaleBtn').addEventListener('click', () => {
        const from = document.getElementById('repSaleFromMonth').value
        const to = document.getElementById('repSaleToMonth').value

        // Check if from and to dates are valid
        if (from && to) {
          const filteredData = data.filter(item => {
            const itemDate = new Date(item.date)
            const fromDate = new Date(from)
            const toDate = new Date(to)
            return itemDate >= fromDate && itemDate <= toDate
          })

          if (filteredData.length > 0) {
            const groupedData = groupDataByRepSales(filteredData)
            displayRepSaleData(groupedData)
            repSaleChart(groupedData)
          } else {
            alert('No data found for the selected period.')
            repSalesHot.loadData([]) // Clear table if no data found
          }
        } else {
          alert('Please select both a start and end date.')
        }
      })
    })
}

let repSalesChartInstance = null // To hold the chart instance

function repSaleChart (groupedData) {
  // Extract relevant data
  const relevantData = Object.values(groupedData).map(data => ({
    rep: data.rep,
    repTotal: data.total_sales
  }))

  // Calculate total sales
  const totalSales = relevantData.reduce((sum, data) => sum + data.repTotal, 0)

  // Calculate percentages
  const chartLabels = relevantData.map(data => data.rep)
  const chartData = relevantData.map(data => ({
    label: data.rep,
    total: data.repTotal,
    percentage: ((data.repTotal / totalSales) * 100).toFixed(2)
  }))

  // Prepare chart datasets
  const percentages = chartData.map(data => data.percentage)
  const salesValues = relevantData.map(data => data.repTotal)

  // Display total sales in the div
  document.getElementById(
    'repSaleTotal'
  ).textContent = `Total Sale: RS. ${totalSales.toLocaleString()}`

  // Destroy the existing chart instance if it exists
  if (repSalesChartInstance) {
    repSalesChartInstance.destroy()
  }

  // Render the chart
  const ctx = document.getElementById('repSalesChart').getContext('2d')
  repSalesChartInstance = new Chart(ctx, {
    type: 'pie', // Pie chart for percentages
    data: {
      labels: chartLabels,
      datasets: [
        {
          label: 'Sales Percentage',
          data: salesValues, // Use raw sales values
          backgroundColor: [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40'
          ],
          hoverOffset: 4
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        datalabels: {
          color: 'black',
          font: {
            size: 14,
            weight: 'bold'
          },
          formatter: (value, context) => {
            const index = context.dataIndex
            const percentage = percentages[index]
            return `${percentage}% (${value.toLocaleString()})`
          }
        },
        tooltip: {
          enabled: true, // Keep tooltips enabled
          callbacks: {
            label: function (tooltipItem) {
              const rep = chartLabels[tooltipItem.dataIndex]
              const percentage = percentages[tooltipItem.dataIndex]
              const sales = salesValues[tooltipItem.dataIndex]
              return `${rep}: ${percentage}% (${sales.toLocaleString()})`
            }
          }
        },
        legend: {
          display: true,
          position: 'bottom'
        }
      }
    },
    plugins: [ChartDataLabels] // Ensure the DataLabels plugin is added
  })
}

// Function to group data by rep and aggregate total sales by month
function groupDataByRepSales (data) {
  const groupedData = {}
  data.forEach(item => {
    const rep = item.rep || 'N/A'
    if (!groupedData[rep]) {
      groupedData[rep] = {
        rep: rep,
        sales_jan: 0,
        sales_feb: 0,
        sales_mar: 0,
        sales_apr: 0,
        sales_may: 0,
        sales_jun: 0,
        sales_jul: 0,
        sales_aug: 0,
        sales_sep: 0,
        sales_oct: 0,
        sales_nov: 0,
        sales_dec: 0,
        total_sales: 0
      }
    }
    const month = new Date(item.date).getMonth()
    const salesMonthKeys = [
      'sales_jan',
      'sales_feb',
      'sales_mar',
      'sales_apr',
      'sales_may',
      'sales_jun',
      'sales_jul',
      'sales_aug',
      'sales_sep',
      'sales_oct',
      'sales_nov',
      'sales_dec'
    ]

    // Aggregate the sales value for each month
    if (item.value !== undefined) {
      groupedData[rep][salesMonthKeys[month]] += parseFloat(item.value || 0)
    }
  })

  // Calculate the total sales for each rep
  Object.values(groupedData).forEach(row => {
    row.total_sales = [
      'sales_jan',
      'sales_feb',
      'sales_mar',
      'sales_apr',
      'sales_may',
      'sales_jun',
      'sales_jul',
      'sales_aug',
      'sales_sep',
      'sales_oct',
      'sales_nov',
      'sales_dec'
    ].reduce((sum, month) => sum + (row[month] || 0), 0)
  })

  return groupedData
}
// Function to normalize rep names
function normalizeRepName (rep) {
  const repMap = {
    A: 'Anjana',
    AD: 'amal',
    AR: 'Arkam',
    AS: 'Shaheer',
    SA: 'sameer'
  }
  return repMap[rep] || rep
}

// Function to group data by rep and aggregate total sales by month
function groupDataByRepSales (data) {
  const groupedData = {}
  data.forEach(item => {
    const rep = normalizeRepName(item.rep || 'N/A')
    if (!groupedData[rep]) {
      groupedData[rep] = {
        rep: rep,
        sales_jan: 0,
        sales_feb: 0,
        sales_mar: 0,
        sales_apr: 0,
        sales_may: 0,
        sales_jun: 0,
        sales_jul: 0,
        sales_aug: 0,
        sales_sep: 0,
        sales_oct: 0,
        sales_nov: 0,
        sales_dec: 0,
        total_sales: 0
      }
    }
    const month = new Date(item.date).getMonth()
    const salesMonthKeys = [
      'sales_jan',
      'sales_feb',
      'sales_mar',
      'sales_apr',
      'sales_may',
      'sales_jun',
      'sales_jul',
      'sales_aug',
      'sales_sep',
      'sales_oct',
      'sales_nov',
      'sales_dec'
    ]

    // Aggregate the sales value for each month
    if (item.value !== undefined) {
      groupedData[rep][salesMonthKeys[month]] += parseFloat(item.value || 0)
    }
  })

  // Calculate the total sales for each rep
  Object.values(groupedData).forEach(row => {
    row.total_sales = [
      'sales_jan',
      'sales_feb',
      'sales_mar',
      'sales_apr',
      'sales_may',
      'sales_jun',
      'sales_jul',
      'sales_aug',
      'sales_sep',
      'sales_oct',
      'sales_nov',
      'sales_dec'
    ].reduce((sum, month) => sum + (row[month] || 0), 0)
  })

  return groupedData
}

// Function to display the grouped data in Handsontable
function displayRepSaleData (groupedData) {
  // Convert object to array and filter out rows with no sales
  let tableData = Object.values(groupedData).filter(row => row.total_sales > 0)

  // Sort by total_sales in descending order
  tableData.sort((a, b) => b.total_sales - a.total_sales)

  // Calculate the totals for each month and overall
  const totalsRow = {
    rep: 'Total', // Label for the totals row
    sales_jan: 0,
    sales_feb: 0,
    sales_mar: 0,
    sales_apr: 0,
    sales_may: 0,
    sales_jun: 0,
    sales_jul: 0,
    sales_aug: 0,
    sales_sep: 0,
    sales_oct: 0,
    sales_nov: 0,
    sales_dec: 0,
    total_sales: 0
  }

  // Sum up each month's sales and total_sales
  tableData.forEach(row => {
    totalsRow.sales_jan += row.sales_jan || 0
    totalsRow.sales_feb += row.sales_feb || 0
    totalsRow.sales_mar += row.sales_mar || 0
    totalsRow.sales_apr += row.sales_apr || 0
    totalsRow.sales_may += row.sales_may || 0
    totalsRow.sales_jun += row.sales_jun || 0
    totalsRow.sales_jul += row.sales_jul || 0
    totalsRow.sales_aug += row.sales_aug || 0
    totalsRow.sales_sep += row.sales_sep || 0
    totalsRow.sales_oct += row.sales_oct || 0
    totalsRow.sales_nov += row.sales_nov || 0
    totalsRow.sales_dec += row.sales_dec || 0
    totalsRow.total_sales += row.total_sales || 0
  })

  // Add the totals row as the last row in the table data
  tableData.push(totalsRow)

  if (repSalesHot) {
    repSalesHot.loadData(tableData)
  } else {
    // Initialize Handsontable
    repSalesHot = new Handsontable(document.getElementById('repSalesTable'), {
      data: tableData,
      columns: [
        { data: 'rep', type: 'text', readOnly: true },
        {
          data: 'sales_jan',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_feb',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_mar',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_apr',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_may',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_jun',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_jul',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_aug',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_sep',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_oct',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_nov',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'sales_dec',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_sales',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        }
      ],
      colHeaders: [
        'Rep',
        'Jan Sales',
        'Feb Sales',
        'Mar Sales',
        'Apr Sales',
        'May Sales',
        'Jun Sales',
        'Jul Sales',
        'Aug Sales',
        'Sep Sales',
        'Oct Sales',
        'Nov Sales',
        'Dec Sales',
        'Total Sales'
      ],
      width: '100%',
      height: 'auto',
      rowHeaders: false,
      stretchH: 'all',
      licenseKey: 'non-commercial-and-evaluation',
      dropdownMenu: ['filter_by_value', 'filter_action_bar'],
      filters: true,
      contextMenu: {
        items: {
          view_rep_details: {
            name: 'View Rep Details',
            callback: function () {
              const rep = hot.getDataAtCell(this.getSelected()[0][0], 0) // Rep column
              alert(`Viewing details for Rep: ${rep}`)
            }
          },
          view_full_details: {
            name: 'View Full Details',
            callback: function () {
              const rep = hot.getDataAtCell(this.getSelected()[0][0], 0) // Customer column
              alert(`Viewing full details for Customer: ${rep}`)
            }
          },
          view_gp_report: {
            name: 'View GP Report',
            callback: function () {
              const rep = hot.getDataAtCell(this.getSelected()[0][0], 0) // Customer column
              alert(`Viewing GP Report for Customer: ${rep}`)
            }
          },
          separator: Handsontable.plugins.ContextMenu.SEPARATOR,
          remove_row: {
            name: 'Remove this row'
          }
        }
      },
      afterLoadData: function (firstLoad) {
        if (firstLoad && repSalesHot) {
          const lastRowIndex = repSalesHot.countRows() - 1
          repSalesHot.getCellMeta(lastRowIndex, 0).className = 'totals-row'
          repSalesHot.render() // Apply the CSS style to the totals row
        }
      },
      cells: function (row, col, prop) {
        const cellProperties = {}

        // Apply custom class to the last row (footer)
        if (row === tableData.length - 1) {
          // Changed from data.length to tableData.length
          cellProperties.className = 'footer-bold'
          cellProperties.renderer = function (
            instance,
            td,
            row,
            col,
            prop,
            value,
            cellProperties
          ) {
            // Apply the appropriate renderer based on column type
            if (col === 0) {
              Handsontable.renderers.TextRenderer.apply(this, arguments)
            } else {
              Handsontable.renderers.NumericRenderer.apply(this, arguments)
            }
            td.style.backgroundColor = '#e6f3ff'
          }
        }

        return cellProperties
      }
    })
  }
}

//------------repSaleGpReport-------------------------repSaleGpReport-------------------------repSaleGpReport-------------------------repSaleGpReport-------------------------cusSalesReport-------------
let repGpHot

// Tab change handler
function populateRepGpData (data) {
  document.getElementById('rep-gp-tab').addEventListener('click', function () {
    const container = document.getElementById('repGpTable')
    const currentYear = new Date().getFullYear()
    const currentYearData = data.filter(
      item => new Date(item.date).getFullYear() === currentYear
    )

    // Group and aggregate the current year data
    const groupedData = groupRepGpData(currentYearData)

    // Display data in the table
    displayRepGpData(groupedData)

    // Filter data on button click
    document.getElementById('repGpBtn').addEventListener('click', () => {
      const from = document.getElementById('repGpFromMonth').value
      const to = document.getElementById('repGpToMonth').value

      // Check if from and to dates are valid
      if (from && to) {
        const filteredData = data.filter(item => {
          const itemDate = new Date(item.date)
          const fromDate = new Date(from)
          const toDate = new Date(to)
          return itemDate >= fromDate && itemDate <= toDate
        })

        if (filteredData.length > 0) {
          const groupedData = groupRepGpData(filteredData)
          displayRepGpData(groupedData)
        } else {
          alert('No data found for the selected period.')
          repGpHot.loadData([]) // Clear table if no data found
        }
      } else {
        alert('Please select both a start and end date.')
      }
    })
  })
}

// Function to group data by rep and aggregate total GP by month
function groupRepGpData (data) {
  const groupedData = {}

  data.forEach(item => {
    const rep = item.rep || 'N/A'
    if (!groupedData[rep]) {
      groupedData[rep] = {
        rep: rep,
        total_gp_jan: 0,
        total_gp_feb: 0,
        total_gp_mar: 0,
        total_gp_apr: 0,
        total_gp_may: 0,
        total_gp_jun: 0,
        total_gp_jul: 0,
        total_gp_aug: 0,
        total_gp_sep: 0,
        total_gp_oct: 0,
        total_gp_nov: 0,
        total_gp_dec: 0,
        total_gp: 0
      }
    }
    const month = new Date(item.date).getMonth()
    const gpMonthKeys = [
      'total_gp_jan',
      'total_gp_feb',
      'total_gp_mar',
      'total_gp_apr',
      'total_gp_may',
      'total_gp_jun',
      'total_gp_jul',
      'total_gp_aug',
      'total_gp_sep',
      'total_gp_oct',
      'total_gp_nov',
      'total_gp_dec'
    ]

    // Aggregate the GP value for each month
    if (item.gp !== undefined) {
      groupedData[rep][gpMonthKeys[month]] += parseFloat(item.gp || 0)
    }
  })

  // Calculate the total GP for each rep
  Object.values(groupedData).forEach(row => {
    row.total_gp = [
      'total_gp_jan',
      'total_gp_feb',
      'total_gp_mar',
      'total_gp_apr',
      'total_gp_may',
      'total_gp_jun',
      'total_gp_jul',
      'total_gp_aug',
      'total_gp_sep',
      'total_gp_oct',
      'total_gp_nov',
      'total_gp_dec'
    ].reduce((sum, month) => sum + (row[month] || 0), 0)
  })

  // Convert groupedData object to an array and filter out rows with zero totals
  const dataArray = Object.values(groupedData).filter(row => row.total_gp > 0)

  // Sort by total GP in descending order
  dataArray.sort((a, b) => b.total_gp - a.total_gp)

  // Calculate monthly totals across all reps
  const monthlyTotals = {
    rep: 'Total',
    total_gp_jan: 0,
    total_gp_feb: 0,
    total_gp_mar: 0,
    total_gp_apr: 0,
    total_gp_may: 0,
    total_gp_jun: 0,
    total_gp_jul: 0,
    total_gp_aug: 0,
    total_gp_sep: 0,
    total_gp_oct: 0,
    total_gp_nov: 0,
    total_gp_dec: 0,
    total_gp: 0
  }

  dataArray.forEach(row => {
    Object.keys(monthlyTotals).forEach(key => {
      if (key !== 'rep') monthlyTotals[key] += row[key] || 0
    })
  })

  dataArray.push(monthlyTotals) // Append the total row

  return dataArray
}

// Function to display the grouped data in Handsontable
function displayRepGpData (data) {
  if (!Array.isArray(data) || data.length === 0) {
    console.error('No data available to display.')
    return
  }

  if (!repGpHot) {
    const container = document.getElementById('repGpTable')
    repGpHot = new Handsontable(container, {
      data: data,
      colHeaders: [
        'Rep',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
        'Total GP'
      ],
      columns: [
        { data: 'rep', readOnly: true },
        {
          data: 'total_gp_jan',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_feb',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_mar',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_apr',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_may',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_jun',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_jul',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_aug',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_sep',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_oct',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_nov',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp_dec',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_gp',
          type: 'numeric',
          readOnly: true,
          className: 'htBold htRight',
          numericFormat: { pattern: '0,0.00' }
        }
      ],
      width: '100%',
      height: 450,
      rowHeaders: false,
      stretchH: 'all',
      licenseKey: 'non-commercial-and-evaluation',
      cells: function (row, col, prop) {
        const cellProperties = {}

        // Apply custom class to the last row (footer)
        if (row === data.length - 1) {
          cellProperties.className = 'footer-bold'
          cellProperties.renderer = function (
            instance,
            td,
            row,
            col,
            prop,
            value,
            cellProperties
          ) {
            Handsontable.renderers.NumericRenderer.apply(this, arguments)
            td.style.backgroundColor = '#e6f3ff'
          }
        }

        return cellProperties
      },
      filters: true
    })
  } else {
    repGpHot.loadData(data)
  }
}

//------------proSalepReport-------------------------proSalepReport-------------------------proSalepReport-------------------------proSalepReport-------------------------cusSalesReport-------------
let proSaleHot

// Tab change handler
function populatepProSaleData (data) {
  document
    .getElementById('pro-sale-tab')
    .addEventListener('click', function () {
      const container = document.getElementById('proSaleTable')
      const currentYear = new Date().getFullYear()
      const currentYearData = data.filter(
        item => new Date(item.date).getFullYear() === currentYear
      )

      // Group and aggregate the current year data
      const groupedData = groupProSaleData(currentYearData)

      // Display data in the table
      displayProSaleData(groupedData)

      // Filter data on button click
      document.getElementById('proSaleBtn').addEventListener('click', () => {
        const from = document.getElementById('proSaleFromMonth').value
        const to = document.getElementById('proSaleToMonth').value

        // Check if from and to dates are valid
        if (from && to) {
          const filteredData = data.filter(item => {
            const itemDate = new Date(item.date)
            const fromDate = new Date(from)
            const toDate = new Date(to)
            return itemDate >= fromDate && itemDate <= toDate
          })

          if (filteredData.length > 0) {
            const groupedData = groupProSaleData(filteredData)
            displayProSaleData(groupedData)
          } else {
            alert('No data found for the selected period.')
            proSaleHot.loadData([]) // Clear table if no data found
          }
        } else {
          alert('Please select both a start and end date.')
        }
      })
    })
}

// Function to group data by rep and aggregate total GP by month
function populatepProSaleData (data) {
  document
    .getElementById('pro-sale-tab')
    .addEventListener('click', function () {
      const container = document.getElementById('proSaleTable')
      const currentYear = new Date().getFullYear()
      const currentYearData = data.filter(
        item => new Date(item.date).getFullYear() === currentYear
      )

      // Group and aggregate the current year data
      const groupedData = groupProSaleData(currentYearData)

      // Display data in the table
      displayProSaleData(groupedData)

      // Filter data on button click
      document.getElementById('proSaleBtn').addEventListener('click', () => {
        const from = document.getElementById('proSaleFromMonth').value
        const to = document.getElementById('proSaleToMonth').value

        // Check if from and to dates are valid
        if (from && to) {
          const filteredData = data.filter(item => {
            const itemDate = new Date(item.date)
            const fromDate = new Date(from)
            const toDate = new Date(to)
            return itemDate >= fromDate && itemDate <= toDate
          })

          if (filteredData.length > 0) {
            const groupedData = groupProSaleData(filteredData)
            displayProSaleData(groupedData)
          } else {
            alert('No data found for the selected period.')
            proSaleHot.loadData([]) // Clear table if no data found
          }
        } else {
          alert('Please select both a start and end date.')
        }
      })
    })
}

// Function to group data by product (brand) and aggregate total sales by month
function groupProSaleData (data) {
  const groupedData = {}

  data.forEach(item => {
    const brand = (item.brand || 'Unknown Product').toLowerCase() // Convert brand name to lowercase
    if (!groupedData[brand]) {
      groupedData[brand] = {
        product: item.brand, // Keep the original case for display
        total_product_sale_jan: 0,
        total_product_sale_feb: 0,
        total_product_sale_mar: 0,
        total_product_sale_apr: 0,
        total_product_sale_may: 0,
        total_product_sale_jun: 0,
        total_product_sale_jul: 0,
        total_product_sale_aug: 0,
        total_product_sale_sep: 0,
        total_product_sale_oct: 0,
        total_product_sale_nov: 0,
        total_product_sale_dec: 0,
        total_product_sale: 0
      }
    }
    const month = new Date(item.date).getMonth()
    const proSaleMonthKeys = [
      'total_product_sale_jan',
      'total_product_sale_feb',
      'total_product_sale_mar',
      'total_product_sale_apr',
      'total_product_sale_may',
      'total_product_sale_jun',
      'total_product_sale_jul',
      'total_product_sale_aug',
      'total_product_sale_sep',
      'total_product_sale_oct',
      'total_product_sale_nov',
      'total_product_sale_dec'
    ]

    // Aggregate the value field for each month
    if (item.value !== undefined) {
      groupedData[brand][proSaleMonthKeys[month]] += parseFloat(item.value || 0)
    }
  })

  // Calculate the total sales for each product
  Object.values(groupedData).forEach(row => {
    row.total_product_sale = [
      'total_product_sale_jan',
      'total_product_sale_feb',
      'total_product_sale_mar',
      'total_product_sale_apr',
      'total_product_sale_may',
      'total_product_sale_jun',
      'total_product_sale_jul',
      'total_product_sale_aug',
      'total_product_sale_sep',
      'total_product_sale_oct',
      'total_product_sale_nov',
      'total_product_sale_dec'
    ].reduce((sum, month) => sum + (row[month] || 0), 0)
  })

  // Convert groupedData object to an array and filter out rows with zero totals
  const dataArray = Object.values(groupedData).filter(
    row => row.total_product_sale > 0
  )

  // Sort by total sales in descending order
  dataArray.sort((a, b) => b.total_product_sale - a.total_product_sale)

  // Calculate monthly totals across all products
  const monthlyTotals = {
    product: 'Total',
    total_product_sale_jan: 0,
    total_product_sale_feb: 0,
    total_product_sale_mar: 0,
    total_product_sale_apr: 0,
    total_product_sale_may: 0,
    total_product_sale_jun: 0,
    total_product_sale_jul: 0,
    total_product_sale_aug: 0,
    total_product_sale_sep: 0,
    total_product_sale_oct: 0,
    total_product_sale_nov: 0,
    total_product_sale_dec: 0,
    total_product_sale: 0
  }

  dataArray.forEach(row => {
    Object.keys(monthlyTotals).forEach(key => {
      if (key !== 'product') monthlyTotals[key] += row[key] || 0
    })
  })

  dataArray.push(monthlyTotals) // Append the total row

  return dataArray
}

// Function to display the grouped data in Handsontable
function displayProSaleData (data) {
  if (!Array.isArray(data) || data.length === 0) {
    console.error('No data available to display.')
    return
  }

  if (!proSaleHot) {
    const container = document.getElementById('proSaleTable')
    proSaleHot = new Handsontable(container, {
      data: data,
      colHeaders: [
        'Product',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
        'Total Sale'
      ],
      columns: [
        { data: 'product', readOnly: true },
        {
          data: 'total_product_sale_jan',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_feb',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_mar',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_apr',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_may',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_jun',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_jul',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_aug',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_sep',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_oct',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_nov',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale_dec',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_sale',
          type: 'numeric',
          readOnly: true,
          className: 'htBold htRight',
          numericFormat: { pattern: '0,0.00' }
        }
      ],
      width: '100%',
      height: 450,
      rowHeaders: false,
      stretchH: 'all',
      licenseKey: 'non-commercial-and-evaluation',
      cells: function (row, col, prop) {
        const cellProperties = {}

        // Dynamically check if the row is the total row
        const isTotalRow = this.instance.getDataAtRow(row)[0] === 'Total'

        if (isTotalRow) {
          cellProperties.className = 'footer-bold'
          cellProperties.renderer = function (
            instance,
            td,
            row,
            col,
            prop,
            value,
            cellProperties
          ) {
            // Use appropriate renderer based on column
            if (col === 0) {
              Handsontable.renderers.TextRenderer.apply(this, arguments)
            } else {
              Handsontable.renderers.NumericRenderer.apply(this, arguments)
            }
            td.style.fontWeight = 'bold'
            td.style.backgroundColor = '#e6f3ff'
          }
        }

        return cellProperties
      },

      filters: true
    })
  } else {
    proSaleHot.loadData(data)
  }
}

//------------proGpReport-------------------------proGpReport-------------------------proGpReport-------------------------proGpReport-------------------------cusSalesReport-------------
let proGpHot

function populatepProGpData (data) {
  document.getElementById('pro-gp-tab').addEventListener('click', function () {
    const container = document.getElementById('proGpTable')
    const currentYear = new Date().getFullYear()
    const currentYearData = data.filter(
      item => new Date(item.date).getFullYear() === currentYear
    )

    // Group and aggregate the current year data
    const groupedData = groupProGpData(currentYearData)

    // Display data in the table
    displayProGpData(groupedData)

    // Filter data on button click
    document.getElementById('proGpBtn').addEventListener('click', () => {
      const from = document.getElementById('proGpFromMonth').value
      const to = document.getElementById('proGpToMonth').value

      // Check if from and to dates are valid
      if (from && to) {
        const filteredData = data.filter(item => {
          const itemDate = new Date(item.date)
          const fromDate = new Date(from)
          const toDate = new Date(to)
          return itemDate >= fromDate && itemDate <= toDate
        })

        if (filteredData.length > 0) {
          const groupedData = groupProGpData(filteredData)
          displayProGpData(groupedData)
        } else {
          alert('No data found for the selected period.')
          proGpHot.loadData([]) // Clear table if no data found
        }
      } else {
        alert('Please select both a start and end date.')
      }
    })
  })
}

function groupProGpData (data) {
  const groupedData = {}

  data.forEach(item => {
    const brand = item.brand || 'Unknown Product'
    if (!groupedData[brand]) {
      groupedData[brand] = {
        product: brand,
        total_product_gp_jan: 0,
        total_product_gp_feb: 0,
        total_product_gp_mar: 0,
        total_product_gp_apr: 0,
        total_product_gp_may: 0,
        total_product_gp_jun: 0,
        total_product_gp_jul: 0,
        total_product_gp_aug: 0,
        total_product_gp_sep: 0,
        total_product_gp_oct: 0,
        total_product_gp_nov: 0,
        total_product_gp_dec: 0,
        total_product_gp: 0
      }
    }
    const month = new Date(item.date).getMonth()
    const proSaleMonthKeys = [
      'total_product_gp_jan',
      'total_product_gp_feb',
      'total_product_gp_mar',
      'total_product_gp_apr',
      'total_product_gp_may',
      'total_product_gp_jun',
      'total_product_gp_jul',
      'total_product_gp_aug',
      'total_product_gp_sep',
      'total_product_gp_oct',
      'total_product_gp_nov',
      'total_product_gp_dec'
    ]

    // Aggregate the gp field for each month
    if (item.gp !== undefined) {
      groupedData[brand][proSaleMonthKeys[month]] += parseFloat(item.gp || 0)
    }
  })

  // Calculate the total GP for each product
  Object.values(groupedData).forEach(row => {
    row.total_product_gp = [
      'total_product_gp_jan',
      'total_product_gp_feb',
      'total_product_gp_mar',
      'total_product_gp_apr',
      'total_product_gp_may',
      'total_product_gp_jun',
      'total_product_gp_jul',
      'total_product_gp_aug',
      'total_product_gp_sep',
      'total_product_gp_oct',
      'total_product_gp_nov',
      'total_product_gp_dec'
    ].reduce((sum, month) => sum + (row[month] || 0), 0)
  })

  const dataArray = Object.values(groupedData).filter(
    row => row.total_product_gp > 0
  )
  dataArray.sort((a, b) => b.total_product_gp - a.total_product_gp)

  // Calculate monthly totals across all products
  const monthlyTotals = {
    product: 'Total',
    total_product_gp_jan: 0,
    total_product_gp_feb: 0,
    total_product_gp_mar: 0,
    total_product_gp_apr: 0,
    total_product_gp_may: 0,
    total_product_gp_jun: 0,
    total_product_gp_jul: 0,
    total_product_gp_aug: 0,
    total_product_gp_sep: 0,
    total_product_gp_oct: 0,
    total_product_gp_nov: 0,
    total_product_gp_dec: 0,
    total_product_gp: 0
  }

  dataArray.forEach(row => {
    Object.keys(monthlyTotals).forEach(key => {
      if (key !== 'product') monthlyTotals[key] += row[key] || 0
    })
  })

  dataArray.push(monthlyTotals) // Append the total row

  return dataArray
}

function displayProGpData (data) {
  if (!Array.isArray(data) || data.length === 0) {
    console.error('No data available to display.')
    return
  }

  if (!proGpHot) {
    const container = document.getElementById('proGpTable')
    proGpHot = new Handsontable(container, {
      data: data,
      colHeaders: [
        'Product',
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
        'Total GP'
      ],
      columns: [
        { data: 'product', readOnly: true },
        {
          data: 'total_product_gp_jan',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_feb',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_mar',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_apr',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_may',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_jun',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_jul',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_aug',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_sep',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_oct',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_nov',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp_dec',
          type: 'numeric',
          readOnly: true,
          numericFormat: { pattern: '0,0.00' }
        },
        {
          data: 'total_product_gp',
          type: 'numeric',
          readOnly: true,
          className: 'htBold htRight',
          numericFormat: { pattern: '0,0.00' }
        }
      ],
      width: '100%',
      height: 450,
      rowHeaders: false,
      stretchH: 'all',
      licenseKey: 'non-commercial-and-evaluation',
      cells: function (row, col, prop) {
        const cellProperties = {}

        // Dynamically check if the row is the total row
        const isTotalRow = this.instance.getDataAtRow(row)[0] === 'Total'

        if (isTotalRow) {
          cellProperties.className = 'footer-bold'
          cellProperties.renderer = function (
            instance,
            td,
            row,
            col,
            prop,
            value,
            cellProperties
          ) {
            // Use appropriate renderer based on column
            if (col === 0) {
              Handsontable.renderers.TextRenderer.apply(this, arguments)
            } else {
              Handsontable.renderers.NumericRenderer.apply(this, arguments)
            }
            td.style.fontWeight = 'bold'
            td.style.backgroundColor = '#e6f3ff'
          }
        }

        return cellProperties
      }
    })
  } else {
    proGpHot.loadData(data)
  }
}

//----------------Overview---------------------Overview---------------------Overview---------------------Overview---------------------Overview---------------------Overview---------------------

let overViewHot

function populateOverView (fetchedData) {
  document
    .getElementById('overView-tab')
    .addEventListener('click', function () {
      const container = document.getElementById('overViewTable')
      const currentYear = new Date().getFullYear()

      // Filter data for the current year
      const currentYearData = fetchedData.filter(item => {
        const itemYear = new Date(item.date).getFullYear()
        return itemYear === currentYear
      })

      // If the Handsontable instance already exists, update its data and return
      if (overViewHot) {
        const updatedData = getTableData(currentYearData) // Filter data for current year
        overViewHot.loadData(updatedData)
        return
      }

      // Generate table data for current year
      const data = getTableData(currentYearData) // Filter data for current year

      // Column headers
      const columns = [
        { title: 'Sales', readOnly: true, width: 150 },
        { title: 'Jan', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Feb', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Mar', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Apr', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'May', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Jun', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Jul', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Aug', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Sep', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Oct', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Nov', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        { title: 'Dec', type: 'numeric', numericFormat: { pattern: '0,0.00' } },
        {
          title: 'Total',
          type: 'numeric',
          numericFormat: { pattern: '0,0.00' },
          readOnly: true
        }
      ]

      // Create Handsontable instance
      overViewHot = new Handsontable(container, {
        data: data,
        columns: columns,
        colHeaders: true,
        rowHeaders: false,
        height: 'auto',
        width: '100%',
        stretchH: 'all',
        licenseKey: 'non-commercial-and-evaluation',
        className: 'htCenter',
        cells (row, col) {
          const cellProperties = {}

          // Make the first column read-only
          if (col === 0) {
            cellProperties.readOnly = true
          }

          // Make the percentage row read-only and format as percentage
          if (row === 2) {
            cellProperties.readOnly = true
            cellProperties.numericFormat = {
              pattern: '0.000%',
              culture: 'en-US'
            }
          }

          // Make the total column read-only
          if (col === 13) {
            cellProperties.readOnly = true
          }

          return cellProperties
        }
      })

      // Update the chart with the current year data initially
      overViewChart(getTableData(currentYearData)) // Call the chart function after populating the table

      // Add event listener for filter button
      document.getElementById('overViewBtn').addEventListener('click', () => {
        const from = document.getElementById('overViewFrom').value
        const to = document.getElementById('overViewTo').value

        // Check if from and to dates are valid
        if (from && to) {
          const fromDate = new Date(from)
          const toDate = new Date(to)

          const filteredData = fetchedData.filter(item => {
            const itemDate = new Date(item.date)
            return itemDate >= fromDate && itemDate <= toDate
          })

          if (filteredData.length > 0) {
            // Update the Handsontable with filtered data
            const updatedData = getTableData(filteredData)
            overViewHot.loadData(updatedData)

            // Update the chart with filtered data
            overViewChart(getTableData(filteredData))
          } else {
            alert('No data found for the selected period.')
            overViewHot.loadData([]) // Clear table if no data found
          }
        } else {
          alert('Please select both a start and end date.')
        }
      })
    })
}

function getTableData (fetchedData) {
  const data = [
    [
      'Sales',
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null
    ],
    [
      'GP',
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null
    ],
    [
      '%',
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null,
      null
    ]
  ]

  // Map fetched data to table structure
  fetchedData.forEach(item => {
    const month = new Date(item.date).getMonth() // Get month index (0 for Jan, 1 for Feb, etc.)
    const salesValue = parseFloat(item.value || 0)
    const gpValue = parseFloat(item.gp || 0)

    // Add sales and GP values to their respective rows
    if (!isNaN(salesValue)) {
      data[0][month + 1] = (data[0][month + 1] || 0) + salesValue
    }
    if (!isNaN(gpValue)) {
      data[1][month + 1] = (data[1][month + 1] || 0) + gpValue
    }
    console.log('data[1][month + 1]:', data[1][month + 1])
  })

  // Calculate totals for Sales and GP
  for (let row = 0; row < 2; row++) {
    data[row][13] = data[row]
      .slice(1, 13)
      .reduce((sum, val) => sum + (val || 0), 0)
  }

  // Calculate percentages for each month
  for (let col = 1; col <= 12; col++) {
    const sales = data[0][col]
    const gp = data[1][col]
    data[2][col] = sales ? gp / sales : null // Avoid division by zero
  }

  // Calculate total percentage (overall GP/Sales)
  const totalSales = data[0][13]
  const totalGP = data[1][13]
  data[2][13] = totalSales ? totalGP / totalSales : null

  return data
}

function overViewChart (data) {
  // Get the display area for the chart
  const displayArea = document.getElementById('overViewChart')

  // Extract the Sales values for the Ogive chart
  const salesData = data[0].slice(1, 13)

  // Filter out null or undefined values
  const filteredSalesData = []
  const validLabels = []
  const allLabels = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec'
  ]

  salesData.forEach((sale, index) => {
    if (sale !== null && sale !== undefined) {
      filteredSalesData.push(sale)
      validLabels.push(allLabels[index])
    }
  })

  // Calculate cumulative sales
  const cumulativeSales = []
  let cumulativeSum = 0
  filteredSalesData.forEach(sale => {
    cumulativeSum += sale
    cumulativeSales.push(cumulativeSum)
  })

  // Debug cumulative sales and labels
  //console.log('Filtered Sales Data:', filteredSalesData);
  //console.log('Cumulative Sales Data:', cumulativeSales);
  //console.log('Valid Labels:', validLabels);

  // Create the chart
  const ctx = displayArea.getContext('2d')
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: validLabels,
      datasets: [
        {
          label: 'Monthly Sales',
          data: filteredSalesData, // Use individual monthly sales data
          borderColor: 'rgba(75, 192, 192, 1)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: true,
          tension: 0.4
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          title: {
            display: true,
            text: 'Months'
          }
        },
        y: {
          title: {
            display: true,
            text: 'Sales'
          },
          beginAtZero: true // Ensure chart starts at 0
        }
      },
      plugins: {
        title: {
          display: true,
          text: 'Monthly Sales Distribution'
        }
      }
    }
  })
}

//-------support codes------------------------------------

// Export to Excel function (can be added if needed)
function exportToExcel () {
  // Add export functionality here
}



// ------------------------------Month Filter--------------------------

$(document).ready(function () {
  $('#monthFilter').datepicker({
    format: 'mm-yyyy',
    startView: 'months',
    minViewMode: 'months',
    autoclose: true
  })

  $('#monthFilter').on('changeDate', function () {
    const value = $(this).val()
    //console.log('Selected month-year:', value);
  })

  // Initialize Select2 filters after page load
  populateFilters()
})


// ---------------------------------------Last Page Btn-----------------------------------

// Initialize on page load

document.getElementById('back').addEventListener('click', () => {
  window.history.back()
})



// ------------------------------------------Load Last visted tab-----------------------------


document.addEventListener("DOMContentLoaded", async function() {


  await fetchMasterInvoice();
  var lastTab = localStorage.getItem('lastTab');
  if (lastTab) {
      var triggerEl = document.querySelector(`#myTab button[data-bs-target="${lastTab}"]`);
      var tab = new bootstrap.Tab(triggerEl);
      tab.show();
  }

  var tabButtons = document.querySelectorAll('#myTab button');
  tabButtons.forEach(function(button) {
      button.addEventListener('shown.bs.tab', function(event) {
          var target = event.target.getAttribute('data-bs-target');
          localStorage.setItem('lastTab', target);
      });
  });
});
