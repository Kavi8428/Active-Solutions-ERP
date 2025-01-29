let ginData = [] // Global variable to store fetched gin data
let ginItems = []
let cachedItemCodes = [] // Global to store fetched item codes
let invoiceData = []
let invoiceItems = []
let stockSerials

async function fetchStock () {
  const loadingScreen = document.getElementById('loadingScreen')
  loadingScreen.style.display = 'flex'
  try {
    const response = await fetch('../../functions/fetchStockSerials.php') // Update with your actual URL
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText)
    }
    const data = await response.json()

    cachedItemCodes = [
      { id: 'SELECT', text: 'SELECT' },
      ...data.map(value => ({
        id: value.item_code,
        text: value.item_code
      }))
    ]

    // Group by item_code
    const groupedData = data.reduce((acc, current) => {
      const existing = acc.find(item => item.item_code === current.item_code)
      if (existing) {
        // If item_code exists, add serial to its serials array
        existing.serials.add(current.serial)
      } else {
        // If item_code does not exist, create a new entry
        acc.push({
          item_code: current.item_code,
          serials: new Set([current.serial]) // Using Set to handle duplicate serials
        })
      }
      return acc
    }, [])

    // Convert serials from Set to Array for the final output
    const finalData = groupedData.map(item => ({
      item_code: item.item_code,
      serials: Array.from(item.serials)
    }))

    stockSerials = finalData
    // Once the data is processed, use it further as needed
  } catch (error) {
    console.error('There was a problem with the fetch operation:', error)
  }
}
fetchStock()

let cachedCustomer = [] // Global variable to store fetched item codes

async function fetchCustomers () {
  try {
    fetch('../../functions/fetchCustomers.php') // Update the path if necessary
      .then(response => response.json())
      .then(data => {
        const partners = []
        const endCustomers = []

        data.forEach(item => {
          if (item.type === 'partner') {
            partners.push(item.company_name)
          } else if (item.type === 'end_customer') {
            endCustomers.push(item.company_name)
          }
        })

        populateCompanies(partners)
        populateCustomers(endCustomers)
      })
      .catch(error => {
        console.error('Error fetching data:', error)
      })
  } catch (error) {
    console.error('There was a problem with the fetch operation:', error)
  }
}
// Call the function to fetch item codes
fetchCustomers()

function populateCustomers (endCustomers) {
  // Select the <select> element
  const selectElement = $('#eCustomer')
  // Clear any existing options
  selectElement.empty()
  // Add a default empty option
  selectElement.append(new Option('None', ''))
  // Iterate through the companies and add each as an <option> to the <select>
  endCustomers.forEach(company => {
    selectElement.append(new Option(company, company))
  })
  // Initialize Select2 on the <select> element
  selectElement.select2({
    placeholder: 'Select a company',
    //theme: 'bootstrap-5', // Adjust theme as needed
    width: '70%' // Adjust width as needed
  })
}

function populateCompanies (companies) {
  // Select the <select> element
  const selectElement = $('#customer')

  // Clear any existing options
  selectElement.empty()

  // Iterate through the companies and add each as an <option> to the <select>
  companies.forEach(company => {
    selectElement.append(new Option(company, company))
  })

  // Initialize Select2 on the <select> element
  selectElement.select2({
    placeholder: 'Select a company',
    //theme: 'bootstrap-5', // Adjust theme as needed
    width: '70%' // Adjust width as needed
  })
}

async function fetchGinData () {
  try {
    const response = await fetch('../../functions/fetchGin.php') // Update with your actual URL
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText)
    }
    const data = await response.json() // Parse response as JSON

    // Store the fetched data in global variable

    // Call function to process or display the fetched data
    setTimeout(() => {
      ginData = data
    }, 1000)
  } catch (error) {
    console.error('There was a problem with the fetch operation:', error)
  }
}
// Call the function to fetch gin data
fetchGinData()

// Function to fetch invoice data from the backend
async function fetchInvoiceData () {
  try {
    const response = await fetch('../../functions/fetchInvoice.php', {
      method: 'POST', // Specify the HTTP method as POST
      headers: {
        'Content-Type': 'application/json' // Specify the content type for the request
      }
    })

    if (!response.ok) {
      throw new Error('Invoice Items not fetched correctly')
    }

    const data = await response.json() // Parse the JSON response

    // Filter data based on the 'code' parameter in the URL
    const urlParams = new URLSearchParams(window.location.search)
    const code = urlParams.get('code') // Get 'code' from the URL

    if (code) {
      const filteredData = data.filter(item => item.inv === code)
      //  console.log('Filtered Invoice Data:', filteredData)

      invoiceData = filteredData

      // Handle the filtered data (e.g., display it in a table or use it elsewhere)
    } else {
      console.log('No "code" parameter found in the URL')
    }
  } catch (error) {
    console.error('Error occurred before fetching invoice:', error)
  }
}

// Call the function to fetch and filter data
fetchInvoiceData()

async function fetchInvoiceItems () {
  await fetch('../../functions/fetchInvoiceItems.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({})
  })
    .then(invItems => {
      if (!invItems.ok) {
        throw new Error('Invoice Items not fetched correctly')
      }
      return invItems.json()
    })
    .then(invItems => {
      invoiceItems = invItems
    })
    .catch(error => console.log('Error occured efore fetch invoice', error))
}

fetchInvoiceItems()

// Example function to display or process fetched gin data
function displayGinData (selectedId) {
  let data = ginData
  let urlId
  const urlParams = new URLSearchParams(window.location.search)
  if (selectedId) {
    urlId = selectedId
  } else {
    urlId = urlParams.get('id')
  }
  const invNum = urlParams.get('code')

  if (urlId) {
    const filteredData = data.filter(x => x.id == urlId)
    if (filteredData.length > 0) {
      const ginItem = filteredData[0]
      // Set eCustomer (corrected to end_customer)
      if (ginItem.end_customer) {
        const endCustomerValue = ginItem.end_customer.trim()
        const eCustomerMatched =
          $('#eCustomer').find(`option[value="${endCustomerValue}"]`).length > 0
        if (eCustomerMatched) {
          $('#eCustomer').val(endCustomerValue).trigger('change')
        } else {
          console.error(
            'eCustomer (end_customer) option not found. Available options:',
            $('#eCustomer').html()
          )
        }
      } else {
        console.warn('No end_customer value in ginItem.')
      }
      // Set customer
      const customerValue = ginItem.customer.trim()
      const customerMatched =
        $('#customer').find(`option[value="${customerValue}"]`).length > 0
      if (customerMatched) {
        $('#customer').val(customerValue).trigger('change')
      } else {
        console.error(
          'Customer option not found. Available options:',
          $('#customer').html()
        )
      }
      // Set other fields
      document.getElementById('ginNo').value = ginItem.id
      document.getElementById('invNo').value = ginItem.invNo
      document.getElementById('ginDate').value = ginItem.date
      document.getElementById('object').value = ginItem.object
    } else {
      console.log('No matching data found for ID:', urlId)
    }
  } else if (invNum) {
    // console.log('invoiceData',invoiceData);
    const filteredInvoice = invoiceData.find(x => {
      return (x.inv = invNum)
    })

    //console.log('filteredInvoice',filteredInvoice)

    if (filteredInvoice && filteredInvoice.customer) {
      const endCustomerValue = filteredInvoice.customer.trim()
      const customerValue = filteredInvoice.customer.trim()

      const eCustomerMatched =
        $('#eCustomer').find(`option[value="${endCustomerValue}"]`).length > 0
      const customerMatched =
        $('#customer').find(`option[value="${customerValue}"]`).length > 0

      if (eCustomerMatched) {
        $('#eCustomer').val(endCustomerValue).trigger('change')
      } else if (customerMatched) {
        $('#customer').val(customerValue).trigger('change')
      } else {
        console.error(
          'eCustomer option not found. Available options:',
          $('#eCustomer').html()
        )
        console.error(
          'Customer option not found. Available options:',
          $('#customer').html()
        )
      }
    } else {
      console.warn('No end_customer value in ginItem.')
    }

    if (filteredInvoice) {
      document.getElementById('invNo').value = filteredInvoice.inv
      document.getElementById('ginDate').value = filteredInvoice.inv_date
      document.getElementById('object').value = filteredInvoice.object
    } else {
      //console.log('filteredInvoice is empty')
    }
  } else {
    //  console.log('URL ID is not found.')
    data.forEach(grnItem => {
      const grnNo = grnItem.id
      document.getElementById('ginNo').value = Number(grnNo) + 1
    })
  }
}

async function fetchGinItems () {
  try {
    const response = await fetch('../../functions/fetchGinItems.php') // Update with your actual URL
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText)
    }
    const data = await response.json() // Parse response as JSON

    // Store the fetched data in global variable
    ginItems = data

    // Call function to process or display the fetched data
    setTimeout(() => {
      displyGinItems()
      loadingScreen.style.display = 'none'
    }, 1000)
  } catch (error) {
    console.error('There was a problem with the fetch operation:', error)
  }
}

fetchGinItems()

let hot

function displyGinItems (selectedId) {
  let urlId
  const urlParams = new URLSearchParams(window.location.search)
  if (selectedId) {
    urlId = selectedId
  } else {
    urlId = urlParams.get('id')
  }
  const invNum = urlParams.get('code')

  let initialData;
  console.log('URL ID:', urlId);
  if (urlId) {
    const filteredItems = ginItems.filter(item => item.gin_id === urlId)
    //  console.log('Filtered GinItems:', filteredItems)

    initialData = filteredItems.map(item => [
      item.id || '',
      item.itemCode || '',
      item.quantity || '',
      item.serial || ''
    ])
  } else if (invNum) {
    const filteredInvoice = invoiceData.filter(x => {
      return (x.inv = invNum)
    })
    // console.log('Fetched invoice items:', invoiceItems)

    // console.log('Filtered invoice id:', filteredInvoice[0].id);
    let filteredItems
    if (filteredInvoice.length > 0) {
      filteredItems = invoiceItems.filter(
        item => item.inv_no == filteredInvoice[0].id
      )
      initialData = filteredItems.map(item => [
        '',
        item.item_code || '',
        item.qt || '',
        item.serials || ''
      ])
    } else {
      //   console.log('Invoice array is empty')
    }

    // console.log('filteredInvoiceItems', filteredInvoice)
  } else {
    //  console.log('URL ID not found')
    initialData = [
      ['', , ''],
      ['', , '']
    ]
  }

  let dummySerials

  // Custom renderer for serial column
  function serialRenderer (instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments)
    td.innerHTML = ''

    // Create container for selected serials
    const container = document.createElement('div')
    container.classList.add(
      'd-flex',
      'flex-wrap',
      'gap-2',
      'align-items-center'
    )

    // If value exists, create tags for selected serials
    if (value) {
      const serials = value.split(',').filter(s => s.trim())
      serials.forEach(serial => {
        const tag = document.createElement('span')
        tag.classList.add('badge', 'bg-secondary', 'position-relative')
        tag.innerHTML = `${serial} <span class="serial-remove position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info">×</span>`

        // Remove tag functionality
        tag.querySelector('.serial-remove').addEventListener('click', e => {
          e.stopPropagation()
          const currentValue = instance.getDataAtCell(row, col)
          const newValue = currentValue
            .split(',')
            .filter(s => s.trim() !== serial)
            .join(',')
          instance.setDataAtCell(row, col, newValue)
        })

        container.appendChild(tag)
      })
    }

    // Add button to open serial selection modal
    const addButton = document.createElement('button')
    addButton.classList.add('btn', 'btn-sm', 'p-1', 'm-0')
    addButton.style.width = '10%'
    addButton.style.height = '100%'
    addButton.textContent = '+'
    addButton.addEventListener('click', () => openSerialModal(row, col))

    container.appendChild(addButton)
    td.appendChild(container)

    return td
  }

  // Handsontable configuration
  hot = new Handsontable(document.getElementById('grnItemsTable'), {
    data: initialData,
    colHeaders: ['ID', 'ITEM', 'QTY', 'SERIALS'],
    columns: [
      {
        type: 'numeric',
        width: '5',
        readOnly: true // Set ID column as read-only
      },
      {
        type: 'dropdown',
        source: cachedItemCodes.map(item => item.text), // Use cached item codes for dropdown
        allowInvalid: false,
        strict: true,
        width: '20'
      },
      {
        type: 'numeric',
        width: '10',
        validator: (value, callback) => {
          callback(/^\d+$/.test(value)) // Only allow numeric values
        }
      },
      {
        type: 'text',
        renderer: serialRenderer,
        width: '70'
      }
    ],
    manualColumnResize: true,
    columnHeader: true,
    rowHeaders: true,
    width: '100%',
    height: 500,
    stretchH: 'all',
    filters: true,
    dropdownMenu: true,
    contextMenu: true,
    licenseKey: 'non-commercial-and-evaluation',
    // Add afterChange hook
    afterChange: function (changes, source) {
      if (source === 'edit') {
        // Ensure it's triggered by user input
        changes.forEach(([row, prop, oldValue, newValue]) => {
          if (prop === 1) {
            // Assuming 'ITEM' column is at index 1
            console.log(`Row ${row}, Selected Item: ${newValue}`)

            const { item_code, serials } =
              stockSerials.find(item => item.item_code === newValue) || {}
            console.log('filtered stockSerials :', serials)

            dummySerials = serials
          }
        })
      }
    }
  })

  // Open serial selection modal
  async function openSerialModal (row, col) {
    const modal = new bootstrap.Modal(document.getElementById('serialModal'))
    const serialList = document.getElementById('serialList')
    const searchInput = document.getElementById('serialSearch')
    const saveButton = document.getElementById('saveSerials')

    // Reset modal
    serialList.innerHTML = ''
    searchInput.value = ''

    // Current selected serials for this cell
    const currentValue = hot.getDataAtCell(row, col)
    const selectedSerials = currentValue
      ? currentValue.split(',').map(s => s.trim())
      : []

    // Populate serial list with checkboxes
    if (!dummySerials) {
      alert('please select an item code first')
      return
    }
    await dummySerials.forEach(serial => {
      const serialItem = document.createElement('label')
      serialItem.classList.add('list-group-item', 'list-group-item-action')

      const checkbox = document.createElement('input')
      checkbox.type = 'checkbox'
      checkbox.value = serial
      checkbox.checked = selectedSerials.includes(serial)
      checkbox.classList.add('form-check-input', 'me-2', 'bg-info')

      serialItem.appendChild(checkbox)
      serialItem.appendChild(document.createTextNode(serial))
      serialList.appendChild(serialItem)
    })

    // Search functionality
    searchInput.addEventListener('input', e => {
      const searchTerm = e.target.value.toLowerCase()
      Array.from(serialList.children).forEach(item => {
        const serial = item.textContent.toLowerCase()
        item.style.display = serial.includes(searchTerm) ? 'block' : 'none'
      })
    })

    // Save selected serials
    saveButton.onclick = () => {
      const checkedSerials = Array.from(
        serialList.querySelectorAll('input:checked')
      ).map(cb => cb.value)
      hot.setDataAtCell(row, col, checkedSerials.join(','))
      modal.hide()
    }

    // Show modal
    modal.show()
  }

  document
    .getElementById('serialModal')
    .addEventListener('hidden.bs.modal', () => {
      const serialList = document.getElementById('serialList')
      const searchInput = document.getElementById('serialSearch')

      // Reset the modal content
      serialList.innerHTML = ''
      searchInput.value = ''
      console.log('btn clicked')
    })
}
function getItemsArray () {
  const hotData = hot.getData()
  const itemsArray = hotData
    .filter(row => row[1] && row[1] !== 'SELECT') // Ignore rows where the first column (itemCode) is empty or "SELECT"
    .map(row => {
      return {
        id: row[0] || '',
        itemCode: row[1] || '',
        quantity: row[2] || '',
        serialNumbers: row[3] ? row[3].split(',').map(s => s.trim()) : []
      }
    })
  console.log('itemsArray', itemsArray)
  return itemsArray
}

// Code for search input

document.getElementById('searchInput').addEventListener('input', function () {
  const searchValue = this.value.toLowerCase()
  const filteredData = ginData.filter(
    item =>
      item.id.toLowerCase().includes(searchValue) ||
      item.customer.toLowerCase().includes(searchValue) ||
      item.end_customer.toLowerCase().includes(searchValue) ||
      item.date.toLowerCase().includes(searchValue)
  )

  const resultsTable = document.getElementById('resultsTable')
  resultsTable.innerHTML = ''

  if (searchValue) {
    filteredData.forEach(item => {
      const row = document.createElement('tr')
      row.innerHTML = `
            <th scope="row">${item.id}</th>
            <td>${item.customer}</td>
            <td>${item.end_customer}</td>
            <td>${item.date}</td>
        `
      row.addEventListener('click', () => {
        displayGinData(item.id);
        displyGinItems (item.id);
        // window.location.href = `ginTemplate.php?code=${item.id}`
      })
      resultsTable.appendChild(row)
    })

    document.getElementById('searchDropdown').style.display = 'block'
  } else {
    document.getElementById('searchDropdown').style.display = 'none'
  }
})

document.addEventListener('click', function (event) {
  if (!event.target.closest('#searchInput')) {
    document.getElementById('searchDropdown').style.display = 'none'
  }
})

// Code for submitting data to data base

function submitForm () {
  //console.log('Submitting form...');
  var customer = document.getElementById('customer').value
  var date = document.getElementById('ginDate').value
  var ginNo = document.getElementById('ginNo').value
  var eCustomer = document.getElementById('eCustomer').value
  var object = document.getElementById('object').value
  var invNo = document.getElementById('invNo').value

  // Front-end validation
  var isValid = true

  if (!invNo) {
    document.getElementById('invNo').style.border = '1px solid red'
    isValid = false
  } else {
    document.getElementById('invNo').style.border = ''
  }

  if (!customer) {
    $('#customer')
      .next('.select2-container')
      .find('.select2-selection')
      .css('border', '1px solid red')
    isValid = false
  } else {
    $('#customer')
      .next('.select2-container')
      .find('.select2-selection')
      .css('border', '')
  }

  if (!date) {
    document.getElementById('ginDate').style.border = '1px solid red'
    isValid = false
  } else {
    document.getElementById('ginDate').style.border = ''
  }

  if (!ginNo) {
    document.getElementById('ginNo').style.border = '1px solid red'
    isValid = false
  } else {
    document.getElementById('ginNo').style.border = ''
  }

  if (!object) {
    document.getElementById('object').style.border = '1px solid red'
    isValid = false
  } else {
    document.getElementById('object').style.border = ''
  }

  if (!isValid) {
    Swal.fire({
      toast: true,
      icon: 'error',
      title: 'Please fill in all required fields!',
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    })
    return // Stop the function if validation fails
  }

  var itemsData = getItemsArray()

  const itemArra = itemsData.map(item => {
    if (
      item.itemCode === '' ||
      item.quantity === '' ||
      item.serialNumbers === ''
    ) {
      return null
    } else {
      if (
        item.itemCode != 'Service' &&
        item.serialNumbers.length !== Number(item.quantity)
      ) {
        return false
      }
    }
    return {
      id: item.id,
      itemCode: item.itemCode,
      quantity: item.quantity,
      serialNumbers: item.serialNumbers
    }
  })
  //console.log("itemArra", itemArra);

  if (itemArra.includes(false)) {
    Swal.fire({
      toast: true,
      icon: 'error',
      title: 'The serial numbers do not match the quantity for some items..!',
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000,
      timerProgressBar: true
    })
    return // Stop the function if validation fails
  }

  if (itemArra.includes(null)) {
    Swal.fire({
      toast: true,
      icon: 'error',
      title: 'Please add atleast one grn item to table..!',
      position: 'top-end',
      showConfirmButton: false,
      timer: 6000,
      timerProgressBar: true
    })
    return // Stop the function if validation fails
  }

  var formData = new FormData()

  // Add general data to the FormData object
  formData.append('customer', customer)
  formData.append('ginNo', ginNo)
  formData.append('date', date)
  formData.append('eCustomer', eCustomer)
  formData.append('object', object)
  formData.append('invNo', invNo)
  formData.append('itemsData', JSON.stringify(itemsData))

  // Log the formData content for debugging
  //   for (var pair of formData.entries()) {
  //     console.log(pair[0] + ': ' + pair[1])
  //   }

  // AJAX request
  $.ajax({
    url: '../../functions/insertGin.php', // Replace with your server endpoint
    type: 'POST',
    data: formData,
    processData: false, // Prevent jQuery from automatically transforming the data into a query string
    contentType: false, // Ensure the correct content type is set
    success: function (response) {
      console.log('Form submitted successfully:', response)
      // Handle success response
      //alert('Success');
      Swal.fire({
        toast: true,
        icon: 'success',
        title: 'GIN created successfully..!',
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      }).then(() => {
        window.location.href =
          './ginTemplate.php?code=' + encodeURIComponent(ginNo)
      })
    },
    error: function (xhr, status, error) {
      console.log('Form submission failed:', error)
      // Handle error response
      alert('Error: ' + error) // Display error message to the user
    }
  })
}

document.getElementById('submit').addEventListener('click', e => {
  e.preventDefault()
  submitForm()
})
