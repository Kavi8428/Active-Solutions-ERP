document.addEventListener('DOMContentLoaded', function () {
  fetchCustomers()
  fetchUsers()
  // fetchItemCode(); // Call fetchItemCode first to populate itemCodes
  fetchStock()
  fetchStockSerials()
  fetchInvoice()
  fetchInvoiceItems()

  const loadingScreen = document.getElementById('loadingScreen')
  loadingScreen.style.display = 'flex'
  setTimeout(() => {
    loadingScreen.style.display = 'none'
  }, 1000)

  const urlParams = new URLSearchParams(window.location.search)
  const urlId = urlParams.get('id')
  if (urlId) {
    fetchInvoice()
      .then(response => {
        populateGeneralData(response, urlId)
      })
      .catch(error => {
        console.error('Error fetching invoice:', error)
      })
  }
})
let stock = []
let array = []
let itemCodes = []
let invoiceItems = {}

function showToast (message, duration = 3000) {
  const toast = document.getElementById('toast')
  toast.textContent = message
  toast.className = 'show'
  setTimeout(() => {
    toast.className = toast.className.replace('show', '')
  }, duration)
}

function fetchInvoiceItems () {
  fetch('../../functions/fetchInvoiceItems.php')
    .then(response => {
      if (!response.ok) {
        throw new Error(
          `Fetch Invoice Items Response not ok (${response.status} ${response.statusText})`
        )
      }
      return response.json()
    })
    .then(data => {
      //  console.log('Fetched Invoice Items:', data);
      invoiceItems = data // Update the global invoiceItems object with the fetched data
      // Now you can use invoiceItems after this point
      // console.log('Updated invoiceItems object:', invoiceItems);
    })
    .catch(error => {
      console.error('Failed to fetch Invoice Items.', error)
    })
}

function fetchStock () {
  fetch('../../functions/fetchStock.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Stock is not responding')
      }
      return response.json()
    })
    .then(response => {
      // console.log('Stock', response);
      itemCodes = response.map(item => item.item_code)
      populateHandsonTable() // Call populateHandsonTable after itemCodes is populated
    })
    .catch(error => {
      console.error('Stock Fetch is not working', error)
    })
}

function fetchStockSerials () {
  fetch('../../functions/fetchStockSerials.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Stock serials is not responding')
      }
      return response.json()
    })
    .then(response => {
      // console.log('Fetch Serials =', response);
      array = response.map(serials => ({
        serial: serials.serial,
        itemCode: serials.item_code
      }))
      // populateHandsonTable();
      // You don't need to call populateHandsonTable here again
    })
    .catch(error => {
      console.error('Fetch stock serial is not ok.', error)
    })
}

let hot

// Function to get initial data from localStorage, or load empty data if none exists
function getInitialData () {
  const savedData = '' // Get saved data from localStorage
  if (savedData) {
    return JSON.parse(savedData) // Return parsed JSON data if found
  } else {
    // Return an array of 10 empty rows if no data is saved
    return Array(10)
      .fill()
      .map(() => ['', '', '', '', '', ''])
  }
}

const removedRows = []

function populateHandsonTable () {
  const container = document.getElementById('invTable')

  // Initialize Handsontable with data loaded from getInitialData()
  hot = new Handsontable(container, {
    data: getInitialData(), // Load the initial table data from localStorage or empty array
    rowHeaders: false,
    colHeaders: [
      'ITEM',
      'QUANTITY',
      'SERIALS',
      'DESCRIPTION',
      'UNIT PRICE',
      'TOTAL',
      'VAT',
      'GP',
      'WARRANTY',
      'OTHERS'
    ],
    columns: [
      {
        data: 'itemCode',
        type: 'dropdown',
        source: itemCodes
      },
      {
        data: 'quantity'
      },
      {
        data: 'serials',
        renderer: function (
          instance,
          td,
          row,
          col,
          prop,
          value,
          cellProperties
        ) {
          $(td).empty()
          const select = $(
            `<select style="border:none;" class="text-sm border-0 bg-blue" multiple="multiple">
            </select>`
          )

          const rowItemCode = instance.getDataAtRowProp(row, 'itemCode')
          const relevantSerials = array
            .filter(item => item.itemCode === rowItemCode)
            .map(item => ({
              id: item.serial,
              text: item.serial
            }))

          relevantSerials.forEach(option => {
            select.append(new Option(option.text, option.id))
          })

          $(td).append(select)
          select.select2({
            dropdownAutoWidth: false,
            width: '100%',
            allowClear: true
          })

          select.val(value ? value.split(',') : []).trigger('change')
          select.on('change', function () {
            const selectedValues = $(this).val()
            instance.setDataAtCell(row, col, selectedValues.join(','))
          })
        }
      },
      {
        data: 'description',
        type: 'text'
      },
      {
        data: 'priceEach',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00'
        }
      },
      {
        data: 'amount',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00'
        },
        readOnly: true
      },
      {
        data: 'vat',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00'
        }
      },
      {
        data: 'gp',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00'
        }
      },
      {
        data: 'warranty',
        type: 'date',
        dateFormat: 'YYYY-MM-DD',
        correctFormat: true
      },
      {
        data: 'others',
        type: 'text'
      }
    ],
    manualColumnResize: true,
    manualRowResize: true,
    contextMenu: true,
    minSpareRows: 1,
    height: '360px',
    width: '100%',
    stretchH: 'all',
    licenseKey: 'non-commercial-and-evaluation',

    // Save data to localStorage whenever a change is made
    afterChange: function (changes, source) {
      if (source !== 'loadData') {
        // Prevent triggering during initial load
        const currentData = hot.getData() // Get the current data from Handsontable
        localStorage.setItem('handsontableData', JSON.stringify(currentData)) // Save it to localStorage
      }
    }
  })
  Handsontable.hooks.add('afterChange', function (changes, source) {
    if (source === 'edit') {
      const data = this.getData();
      data.forEach((row, index) => {
        if (row[1] && row[4]) {
          const quantity = parseFloat(row[1]);
  
          // Check if row[4] is a string before calling replace
          const price = typeof row[4] === 'string' 
            ? parseFloat(row[4].replace(/[^0-9.-]+/g, '')) // Clean the price value if it's a string
            : parseFloat(row[4]); // Directly parse if it's a number
  
          if (!isNaN(quantity) && !isNaN(price)) {
            const amount = quantity * price;
            this.setDataAtCell(index, 5, amount.toFixed(2), 'calculate');
          }
        }
      });
    }
  });
  
  

  // Function to calculate the Total
  Handsontable.hooks.add('afterChange', function (changes, source) {
    if (source === 'edit' || source === 'calculate') {
      const data = this.getData()
      let total = 0
      data.forEach(row => {
        if (row[5]) {
          total += parseFloat(row[5])
        }
      })
      //  console.log('Totals', total.toFixed(2));
    }
  })
  Handsontable.hooks.add('beforeRemoveRow', function (index, amount) {
    for (let i = index; i < index + amount; i++) {
      // Fetch the entire row data
      const rowData = hot.getDataAtRow(i)
      if (rowData) {
        removedRows.push(rowData)
      }
    }
    // console.log('Removed rows:', removedRows);
  })
}

// Function to populate new data from an invoice item
function populateInvoiceItems (selectedItem) {
  // console.log('selectedItem', selectedItem);

  // console.log('invoiceItems', invoiceItems);

  const matchedData = invoiceItems.filter(item => item.inv_no === selectedItem)
  // console.log('matchedData', matchedData);

  if (matchedData.length > 0) {
    // Prepare the new data to be loaded into the table
    const newData = matchedData.map(item => ({
      itemCode: item.item_code,
      quantity: item.qt,
      serials: item.serials,
      description: item.description,
      priceEach: item.unit_price,
      amount: item.total,
      vat: item.vat,
      gp: item.gp,
      warranty: item.warranty
    }))

    // Load the new data into the existing Handsontable instance
    hot.loadData(newData)
  } else {
    console.error('No matching invoice item found for inv_no:', selectedItem)
  }
}

// Function to collect Handsontable data
function sendData () {
  // Create an object to store general data
  let generalData = {}

  // Collect form data
  const id = document.getElementById('invNo').value
  const inv = document.getElementById('inv').value
  const customer = document.getElementById('customer').value
  const cusEmployee = document.getElementById('cusEmployee').value
  const invDate = document.getElementById('invDate').value
  const poNo = document.getElementById('poNo').value
  const rep = document.getElementById('rep').value
  const terms = document.getElementById('terms').value
  const ship = document.getElementById('shipping').value
  const vat = document.getElementById('vat').value
  const discountType = document.getElementById('discountType').value
  const discountValue = document.getElementById('discountValue').value
  const object = document.getElementById('object').value //
  const status = document.getElementById('status').value //status
  const inventory = document.getElementById('inventory').checked //inventory
  const lInvNo = document.getElementById('lInvNo').value //Legacy invoice number


  // Validate form data
  const fields = [
    { id: 'invNo', value: id, name: 'Invoice Number' },
    { id: 'inv', value: inv, name: 'Invoice' },
    { id: 'invDate', value: invDate, name: 'Invoice Date' },
    { id: 'rep', value: rep, name: 'Representative' },
    { id: 'shipping', value: ship, name: 'Shipping' },
    { id: 'vat', value: vat, name: 'VAT' },
    { id: 'object', value: object, name: 'Object' }
  ]

  let isValid = true
  let isCode = true
  fields.forEach(field => {
    const element = document.getElementById(field.id)
    if (!field.value || (element.tagName === 'SELECT' && !element.value)) {
      element.style.border = '1px solid red'
      isValid = false
    } else {
      element.style.border = ''
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
  })

  if (!isValid) {
    Swal.fire({
      toast: true,
      icon: 'error',
      title: 'Please fill all required fields...',
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true
    })
    return // Stop further execution if validation fails
  } else {
    const tableData = hot.getData()
    let tableValid = true
    const requiredKeys = [0, 1, 2, 3, 4, 5, 8]
    const columnHeaders = [
      'ITEM',
      'QUANTITY',
      'SERIALS',
      'DESCRIPTION',
      'UNIT PRICE',
      'TOTAL',
      'VAT',
      'GP',
      'WARRANTY',
      'OTHERS'
    ]
    // Filter out rows with all null or empty values
    const filteredData = tableData.filter(row => {
      // Check if at least one cell in the row is not null or empty
      return row.some(cell => cell !== null && cell !== '')
    })

    if (filteredData.length === 0) {
      // console.error('No data available in the table.');
      Swal.fire({
        toast: true,
        icon: 'error',
        title: `No data available in the table.`,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true
      })
      return // Stop further execution if no data is available
    } else {
      if (status != 'mInvoice') {
        // console.log('Status =',status);
        filteredData.forEach((row, rowIndex) => {
          requiredKeys.forEach(key => {
            if (!row[key]) {
              tableValid = false
              Swal.fire({
                toast: true,
                icon: 'error',
                title: `Line ${rowIndex + 1} "${
                  columnHeaders[key]
                }" is Required `,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
              })
              return (isCode = false) // Stop further execution if validation fails
            } else {
              const quantity = parseInt(row[1], 10)
              const serials = row[2] ? row[2].split(',') : []
              if (quantity !== serials.length) {
                tableValid = false
                Swal.fire({
                  toast: true,
                  icon: 'error',
                  title: `Line ${
                    rowIndex + 1
                  } "QUANTITY" and "SERIALS" count must match`,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 4000,
                  timerProgressBar: true
                })
                return (isCode = true) // Stop further execution if validation fails
              }
            }
          })
        })
      } else {
        // console.log('Status =',status);
        const firstRow = filteredData[0]
        requiredKeys.forEach(key => {
          if (!firstRow[key]) {
            tableValid = false
            Swal.fire({
              toast: true,
              icon: 'error',
              title: `Line 1 "${columnHeaders[key]}" is Required `,
              position: 'top-end',
              showConfirmButton: false,
              timer: 4000,
              timerProgressBar: true
            })
            return (isCode = false) // Stop further execution if validation fails
          } else {
            const quantity = parseInt(firstRow[1], 10)
            const serials = firstRow[2] ? firstRow[2].split(',') : []
            if (quantity !== serials.length) {
              tableValid = false
              Swal.fire({
                toast: true,
                icon: 'error',
                title: `Line 1 "QUANTITY" and "SERIALS" count must match`,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
              })
              return (isCode = true) // Stop further execution if validation fails
            }
          }
        })
      }
    }
  }

  // Add form data to the generalData object
  generalData['id'] = id
  generalData['inv'] = inv
  generalData['customer'] = customer
  generalData['cusEmployee'] = cusEmployee
  generalData['invDate'] = invDate
  generalData['poNo'] = poNo
  generalData['rep'] = rep
  generalData['terms'] = terms
  generalData['shipping'] = ship
  generalData['vat'] = vat
  generalData['discountType'] = discountType
  generalData['discountValue'] = discountValue
  generalData['object'] = object
  generalData['status'] = status 
  generalData['inventory'] = inventory
  generalData['lInvNo'] = lInvNo

  //------------------------Handson Table Collection Start---------------------------------------------->

  // Access the Handsontable instance and get the table data
  const tableData = hot.getData()
  const columnHeaders = [
    'ITEM',
    'QUANTITY',
    'SERIALS',
    'DESCRIPTION',
    'UNIT PRICE',
    'TOTAL',
    'VAT',
    'GP',
    'WARRANTY',
    'OTHERS'
  ]
  // Filter out rows with all null or empty values
  const filteredData = tableData.filter(row => {
    // Check if at least one cell in the row is not null or empty
    return row.some(cell => cell !== null && cell !== '')
  })

  filteredData.forEach((row, rowIndex) => {
    // Validate quantity and serial count match
  })

  // Map filtered data to an array of objects using the column headers
  const invItems = filteredData.map(row => {
    let rowObject = {}
    columnHeaders.forEach((header, index) => {
      rowObject[header] = row[index] // Map the column header to the row value
    })
    return rowObject
  })

  console.log('removedRows',removedRows)

  //-----------------------End Of handson table collection-------------------------->

  // // Send the data to the server or use it as needed
  

  if (isCode === true) {
    console.log('invItems',invItems);
    $.ajax({
      url: '../../functions/updateInvoice.php',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        generalData: generalData,
        removedRows: removedRows,
        invItems: invItems
      }),
       // Expect JSON response from the server
        _success: function (response) {
          // Handle the successful response
       // console.log('normal invoice Response text:', response) // Log the response text for debugging

          showToast('Data Saved Successfully')
          setTimeout(() => {
            window.location.href = './invoice_view.php?id=' + generalData.inv;
          }, 1500) // Wait for the toast to show off before reloading the page
        },
      get success() {
        return this._success
      },
      set success(value) {
        this._success = value
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error(
          'normal invoice Error sending data to server:',
          textStatus,
          errorThrown
        )
     //   console.log('normal invoice Response text:', jqXHR.responseText) // Log the response text for debugging
      }
    })

    // Find the first item in invItems to match PHP script's expected parameters
    const itemDataList = invItems.length > 0 ? invItems : []

    // Fetch items from the server
    fetch('../../functions/fetchItems.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('Error while fetching ITEMS')
        }
        return response.json()
      })
      .then(response => {
        // Create an array to store data for each item
        const dataToSendArray = itemDataList.map(itemData => {
          const matchedItem = response.find(
            item => item.item_code === itemData['ITEM']
          )
          const brand = matchedItem ? matchedItem.brand : ''

          return {
            id: '',
            inv: generalData.inv,
            grn: generalData.grn || '',
            gin: generalData.gin || '',
            customer: generalData.customer,
            cusEmployee: generalData.cusEmployee,
            itemCode: itemData['ITEM'] || '',
            item: itemData['ITEM'] || '',
            itemDescription: itemData['DESCRIPTION'] || '',
            brand: brand,
            serial: itemData['SERIALS'] || '',
            value: itemData['UNIT PRICE'] || '',
            qty: itemData['QUANTITY'] || '',
            object: generalData.object || '',
            status: generalData.status || '',
            warranty: itemData['WARRANTY'] || '',
            gp: itemData['GP'] || '',
            rep: generalData.rep,
            date: generalData.invDate,
            memo: itemData['DESCRIPTION'] || '',
            vat: itemData['VAT'] || '',
            others: itemData['OTHERS'] || '',
            lInvNo: generalData['lInvNo'] || ''
          }
        })

       // console.log('dataToSendArray', dataToSendArray)

        // Send each item individually to match the PHP script requirements
        dataToSendArray.forEach(item => {
          $.ajax({
            url: '../../functions/updateMasterInv.php',
            method: 'POST',
            contentType: 'application/x-www-form-urlencoded', // Form-encoded content type
            data: item, // Send each item directly, matching PHP $_POST expectations
            dataType: 'text',
            success: function (response) {
              console.log('master invoice Server response:', response)
              if (response.message) {
                showToast(response.message)
                window.location.href = './invoice_view.php?id=' + item.inv;
              } else if (response.error) {
                console.error('Error:', response.error)
              }
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.error(
                'master invoice Error sending data:',
                textStatus,
                errorThrown
              )
              console.log('master invoice Response text:', jqXHR.responseText)
            }
          })
        })
      })
      .catch(error => {
        console.log('Error while fetching ITEMS', error)
      })
  } else {
    //  console.log('go chk again');
    isCode = false
    return
  }
}

function fetchInvoice () {
  invNo = document.getElementById('invNo')
  return fetch('../../functions/fetchInvoice.php')
    .then(response => {
      if (!response.ok) {
        throw new Error(
          `Fetch Invoice Response not ok (${response.status} ${response.statusText})`
        )
      }
      return response.json()
    })
    .then(response => {
      if (response === null || Object.keys(response).length === 0) {
        invNo.value = 1
      } else {
        if (response.length > 0) {
          const latestInvoice = response.reduce((max, current) => {
            return max.created_at > current.created_at ? max : current
          }, response[0])
          invNo.value = Number(latestInvoice.id) + 1
        } else {
          invNo.value = 1
        }
      }

      const urlParams = new URLSearchParams(window.location.search)
      const urlId = urlParams.get('id')
      const urlInv = urlParams.get('invNo')
      if (urlId) {
        populateGeneralData(response, { urlId: urlId })
      } else if (urlInv) {
        populateGeneralData(response, { urlInv: urlInv })
      } else {
        populateGeneralData(response)
      }
      return response
    })
    .catch(error => {
      throw error
    })
}

function populateGeneralData (response, urlValue) {
  const id = document.getElementById('invNo')
  const inv = document.getElementById('inv')
  const customer = $('#customer')
  const invDate = document.getElementById('invDate')
  const poNo = document.getElementById('poNo')
  const rep = $('#rep')
  const terms = document.getElementById('terms')
  const ship = document.getElementById('shipping')
  const vat = document.getElementById('vat')
  const discountType = document.getElementById('discountType')
  const discountValue = document.getElementById('discountValue')
  const object = document.getElementById('object')
  const status = document.getElementById('status')
  const lInvNo = document.getElementById('lInvNo')
  const inventory = document.getElementById('inventory')
  const searchInput = $('#search')

  customer.select2({
    placeholder: 'Select Customer',
    width: '100%',
    data: response.map(item => ({
      id: item.customer,
      text: item.customer
    }))
  })

  rep.select2({
    placeholder: 'Select Rep',
    width : 'resolve',
    data: response.map(item => ({
      id: item.rep,
      text: item.rep
    }))
  })

  searchInput.select2({
    placeholder: 'Select INV',
    width: '100%',
    data: response.map(item => ({
      id: item.inv,
      text: item.inv
    }))
  })

  if (urlValue) {
    console.log('response:',response);
    console.log('urlValue:',urlValue);

    const matchedData = typeof urlValue === 'object' && urlValue.urlId
      ? response.find(item => String(item.id).trim() === String(urlValue.urlId).trim())
      : response.find(item => String(item.inv).trim() === String(urlValue).trim())

    if (matchedData) {
      populateInvoiceItems(matchedData.id)
      id.value = matchedData.id
      inv.value = matchedData.inv
      customer.val(matchedData.customer).trigger('change')
      invDate.value = matchedData.inv_date
      poNo.value = matchedData.po_num
      rep.val(matchedData.rep).trigger('change')
      terms.value = matchedData.terms
      ship.value = matchedData.shipping_date
      vat.value = matchedData.vat
      discountValue.value = matchedData.discountValue
      discountType.value = matchedData.discountStatus
      object.value = matchedData.object
      status.value = matchedData.status
      inventory.checked = matchedData.inventory
      lInvNo.value = matchedData.lInvNo

      populateCusEmployee(matchedData.customer, matchedData.cusEmployee)
    } else {
      console.log('No match found')
    }
  }

  searchInput.on('select2:select', function (e) {
    const selectedId = e.params.data.id
    const matchedData = response.find(item => item.inv === selectedId)
    if (matchedData) {
      console.log('matchedData', matchedData)
      populateInvoiceItems(matchedData.id)
      id.value = matchedData.id
      inv.value = matchedData.inv
      customer.val(matchedData.customer).trigger('change')
      invDate.value = matchedData.inv_date
      poNo.value = matchedData.po_num
      rep.val(matchedData.rep).trigger('change')
      terms.value = matchedData.terms
      ship.value = matchedData.shipping_date
      vat.value = matchedData.vat
      discountValue.value = matchedData.discountValue
      discountType.value = matchedData.discountStatus
      object.value = matchedData.object
      status.value = matchedData.status
      inventory.checked = matchedData.inventory == 1
      lInvNo.value = matchedData.lInvNo
      populateCusEmployee(matchedData.customer, matchedData.cusEmployee)
    } else {
      console.log('No match found')
    }
  })
}

function fetchUsers () {
  fetch('../../functions/fetchUsers.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('User fetech part is not working')
      }
      return response.json()
    })
    .then(users => {
      // console.log('users',users);
      populateUsers(users)
    })
    .catch(error => {
      console.error('Fetch Process is not working.')
    })
}

function populateUsers (users) {
  //console.log('users', users);

  const selectElement = document.getElementById('rep')

  // Clear existing options
  selectElement.innerHTML = '<option value="">SELECT</option>'

  // Add user names to the select element
  users.forEach(user => {
    const option = document.createElement('option')
    option.value = user.user_name // Using id as the value
    option.textContent = user.user_name
    selectElement.appendChild(option)
  })

  // Initialize Select2
  $(document).ready(function () {
    $('#rep').select2({
      placeholder: 'Select a representative',
      allowClear: true
    })
  })
}

function fetchCustomers () {
  fetch('../../functions/fetchCompany.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Fetch Part Is not working. network err', err)
      }
      return response.json()
    })
    .then(companies => {
      //console.log(companies)
      populateCompany(companies)
    })
    .catch(error => {
      console.error(
        'There is err in fetch opereation. please contact developer.'
      )
    })
}

function populateCompany (companies) {
  //console.log(companies)

  const selectElement = document.getElementById('customer')

  // Clear existing options
  selectElement.innerHTML = '<option value="">SELECT</option>'

  // Add companies to the select element
  companies.forEach(company => {
    const option = document.createElement('option')
    option.value = company
    option.textContent = company
    selectElement.appendChild(option)
  })

  $('#cusEmployee').select2({
    placeholder: 'Select',
    allowClear: true
  })

  // Initialize Select2
  $(document)
    .ready(function () {
      $('#customer').select2({
        placeholder: 'Select a company',
        allowClear: true,
        width: 'resolve' // Use 'resolve' to maintain the width
      })
    })
    .on('select2:select', function (e) {
      const selectedValue = $('#customer').select2('val')
      //console.log('Selected value:', populateCusEmployee(customer));
      populateCusEmployee(selectedValue)
    })
}

function populateCusEmployee (customer, selectedEmployee) {
  // console.log('selectedEmployee', selectedEmployee);
  $.ajax({
    url: '../../functions/fetchEmployees.php', // Replace with the actual path to your PHP script
    type: 'GET',
    data: {
      company: customer
    },
    dataType: 'json',
    success: function (response) {
      // console.log('response', response);

      if (response.cusEmpName && Array.isArray(response.cusEmpName)) {
        let employeeNames = response.cusEmpName

        // Clear previous options
        //  $('#cusEmployee').empty().append(new Option('Select', '', false, false));

        // Populate the select menu with employee names
        employeeNames.forEach(function (name) {
          $('#cusEmployee').append(new Option(name, name))
        })

        // Initialize Select2 without resetting
        $('#cusEmployee').select2({
          placeholder: 'Select Employee',
          allowClear: true,
          width : 'resolve'
        })

        // Set the selected employee after ensuring options are populated
        if (selectedEmployee) {
          setTimeout(() => {
            $('#cusEmployee').val(selectedEmployee).trigger('change')
          }, 100) // Adjust the delay if necessary
        }
      } else {
        console.error('No employee names found for this company')
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error:', error)
    }
  })
}
