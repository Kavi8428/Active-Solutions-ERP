document.addEventListener('DOMContentLoaded', function () {
  // Add click handler for submenu toggles
  document
    .querySelectorAll('.dropdown-submenu > .dropdown-toggle')
    .forEach(function (element) {
      element.addEventListener('click', function (e) {
        e.preventDefault()
        e.stopPropagation()

        // Close all other submenus
        let allSubmenus = document.querySelectorAll(
          '.dropdown-submenu .dropdown-menu'
        )
        allSubmenus.forEach(function (menu) {
          if (menu !== e.target.nextElementSibling) {
            menu.style.display = 'none'
          }
        })

        // Toggle current submenu
        let submenu = e.target.nextElementSibling
        submenu.style.display =
          submenu.style.display === 'block' ? 'none' : 'block'
      })
    })

  // Close submenus when clicking outside
  document.addEventListener('click', function (e) {
    if (!e.target.closest('.dropdown-menu')) {
      document
        .querySelectorAll('.dropdown-submenu .dropdown-menu')
        .forEach(function (menu) {
          menu.style.display = 'none'
        })
    }
  })
})
document.getElementById('fileInput').addEventListener('change', function (e) {
  const file = e.target.files[0];

  if (file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      const data = new Uint8Array(e.target.result);
      const workbook = XLSX.read(data, { type: 'array' });

      let invoiceData = [];

      workbook.SheetNames.forEach(sheetName => {
        const worksheet = workbook.Sheets[sheetName];
        const jsonData = XLSX.utils.sheet_to_json(worksheet);

        jsonData.forEach(element => {
          if (element.Date) {
            element.Date = convertExcelDate(element.Date);
          }

          if (element.Memo) {
            const serials = element.Memo.match(/S\/N\s*:\s*([A-Z0-9\s]+)/i);
            element.Serials = serials && serials[1] ? serials[1].split(/\s+/).filter(Boolean) : [];
          }

          if (element.Item) {
            const itemMatch = element.Item.match(/:(.*?)\s*\(/);
            element.ItemCode = itemMatch && itemMatch[1] ? itemMatch[1] : (element.Item.match(/^(.*?)\s*\(/) || [])[1] || element.Item;
          }

          if (element.Item) {
            const brandMatch = element.Item.match(/^(.*?):/);
            element.Brand = brandMatch ? brandMatch[1] : '';
          }
        });

        let filteredData = jsonData.filter(x => x.Num && x.Num !== '');

        invoiceData = filteredData.map(item => {
          let rep;
          if (item.Rep == 'AS') rep = 'Shaheer';
          else if (item.Rep == 'AD') rep = 'Amal';
          else if (item.Rep == 'A') rep = 'Anjana';

          return {
            inv: item.Num,
            customer: item.Name || '',
            inv_date: item.Date || '',
            po_num: item['P. O. #'] || '',
            rep: rep || '',
            terms: item.Terms || '',
            shipping_date: item.Date || '',
            vat: item.VAT || '',
            discountValue: item.Discount || '',
            discountStatus: item.DiscountStatus || '',
            cusEmployee: item.CusEmployee || '',
            object: item.Object || 'sale',
            status: item.Type || '',
            inventory: item.Inventory || '1',
            lInvNo: item.LInvNo || '',
            invoiceItems: {
              item_code: item.ItemCode || '',
              qt: item.Qty || '',
              serials: item.Serials.toString() || '',
              description: item.Memo || '',
              unit_price: item['Sales Price'] || '',
              total: item.Amount || '',
              vat: item.VAT || '',
              warranty: item.Warranty || '',
              gp: item.GP || ''
            }
          };
        });

        // console.log('invoiceData', invoiceData); // Debugging

        // **SEND DATA TO BACKEND USING FETCH**
        fetch('../../functions/insertImportInvoice.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(invoiceData)
        })
          .then(response => response.json())
          .then(data => {
            console.log('Success:', data);
            // alert('Data sent successfully!');
          })
          .catch(error => {
            console.error('Error:', error);
            // alert('Failed to send data.');
          });
      });
    };

    // const mapData = jsonData.map(element => {
        //   return {
        //     inv: element.Num || '', // Default to an empty string if the value is missing
        //     customer: element.Name || '',
        //     itemCode: element.Model || '',
        //     item: element.Model || '',
        //     itemDescription: element['Item Description'] || '',
        //     brand: element.Brand || '',
        //     serial: '',
        //     value: element.Amount || 0, // Default to 0 if missing
        //     qty: element.Qty || 0, // Default to 0 if missing
        //     object: '',
        //     warranty: element.Warranty || ' ', // Ensure `element.Warranty` exists or set default
        //     date: element.Date ? convertExcelDate(element.Date) : '', // Convert if Date exists
        //     memo: element.Memo || '',
        //     rep: element.Rep || '',
        //     vat: element['VAT Amout'] || 0, // Default to 0 if missing
        //     gp: element['" GP "'] || 0 // Default to 0 if missing
        //   }
        // })

        // console.log('mapData', mapData);
        // updateMasterInv(mapData)

    reader.readAsArrayBuffer(file);
  }
});


function masterFileFilter (jsonData) {
  const summaryData = {}

  jsonData.forEach(item => {
    const num = item.Num
    const brand = item.Brand || 'other'
    const amount = item.Amount || 0

    if (!summaryData[num]) {
      summaryData[num] = {
        inv: num,
        InvDate: convertExcelDate(item.Date),
        soldTo: item.Name,
        totalInvValue: 0,
        Synology: 0,
        bdcom: 0,
        Draytec: 0,
        hardDrives: 0,
        acronis: 0,
        gaj: 0,
        maintain: 0,
        labour: 0,
        other: 0
      }
    }

    summaryData[num].totalInvValue += amount

    switch (brand.toLowerCase()) {
      case 'synology':
        summaryData[num].Synology += amount
        break
      case 'bdcom':
        summaryData[num].bdcom += amount
        break
      case 'draytec':
        summaryData[num].Draytec += amount
        break
      case 'hdd / seagate':
      case 'hdd / hat3300':
      case 'ssd / samsung':
      case 'hdd / toshiba':
      case 'ssd / synology m.2':
      case 'hdd / wd':
      case 'hdd / hat5300':
        summaryData[num].hardDrives += amount
        break
      case 'acronis':
        summaryData[num].acronis += amount
        break
      case 'gajshield':
        summaryData[num].gaj += amount
        break
      case 'ma':
        summaryData[num].maintain += amount
        break
      case 'labour charges':
        summaryData[num].labour += amount
        break
      default:
        summaryData[num].other += amount
        break
    }
  })

  // console.log('summaryData', summaryData);

  // Send the summaryData to the PHP backend script
  fetch('../../functions/updateMasterFile.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(summaryData)
  })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        console.log('Data inserted/updated successfully:', data.message)
      } else {
        console.error('Server Error:', data.message, data.errors)
      }
    })
    .catch(error => {
      console.error('Fetch Error:', error)
    })
}

// Example usage

function updateMasterInv (data) {
  const totalItems = data.length
  let completedItems = 0
  const startTime = Date.now()

  // Create an HTML element for displaying progress
  const progressBar = document.getElementById('progressBar') // Assume an HTML progress element
  const progressText = document.getElementById('progressText') // For displaying text like countdown

  // Clear previous progress
  progressBar.value = 0
  progressText.innerText = `Starting upload...`

  // Function to send a single request
  const sendRequest = element => {
    const xhr = new XMLHttpRequest()
    xhr.open('POST', '../../functions/updateMasterInv.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

    // Handle progress
    xhr.upload.onprogress = function (event) {
      if (event.lengthComputable) {
        const percentComplete = Math.round(
          (completedItems / totalItems) * 100 +
            (event.loaded / event.total) * (100 / totalItems)
        )
        progressBar.value = percentComplete // Update the progress bar
        progressText.innerText = `Uploading ${
          completedItems + 1
        }/${totalItems}: ${percentComplete}% completed`
      }
    }

    // Handle completion
    xhr.onload = function () {
      completedItems++
      if (xhr.status === 200) {
        //  console.log('Response:', xhr.responseText);

        // Update remaining time after each item completes
        const remainingTime = estimateRemainingTime(
          startTime,
          completedItems,
          totalItems
        )
        progressText.innerText = `Remaining time: ${remainingTime}s`

        if (completedItems === totalItems) {
          progressText.innerText = 'Upload completed!'
          location.reload()
        }
      } else {
        console.error('Upload Error:', xhr.statusText)
        progressText.innerText = 'Upload failed. Please try again.'
      }

      // Move to the next request after completion
      if (completedItems < totalItems) {
        sendRequest(data[completedItems]) // Send the next request
      }
    }

    // Handle errors
    xhr.onerror = function () {
      console.error('Request Error:', xhr.statusText)
      progressText.innerText = 'Request failed. Please check your connection.'
    }

    console.log('vat:', element.vat, ',rep:', element.rep)

    // Send request with form data
    xhr.send(
      new URLSearchParams({
        id: '',
        inv: element.inv,
        grn: '',
        gin: '',
        customer: element.customer,
        itemCode: element.itemCode,
        item: element.item,
        itemDescription: element.itemDescription,
        brand: element.brand,
        serial: '',
        value: element.value,
        qty: element.qty,
        object: '',
        warranty: element.warranty,
        date: element.date,
        memo: element.memo,
        vat: element.vat,
        rep: element.rep,
        gp: element.gp
      })
    )
  }

  // Start the first request
  if (totalItems > 0) {
    sendRequest(data[0])
  }

  // Function to estimate remaining time based on actual completion times
  function estimateRemainingTime (startTime, completed, total) {
    if (completed === 0) return 0 // Avoid division by zero
    const elapsedTime = (Date.now() - startTime) / 1000 // in seconds
    const averageTimePerItem = elapsedTime / completed
    const remainingItems = total - completed
    return Math.max(0, Math.round(averageTimePerItem * remainingItems)) // estimated remaining time in seconds
  }
}

function convertExcelDate (excelDate) {
  if (!excelDate || isNaN(excelDate)) {
    return null
  }
  const excelEpoch = new Date(1899, 11, 30)
  const date = new Date(excelEpoch.getTime() + excelDate * 86400000)

  // Convert to Sri Lankan time (UTC+5:30)
  const sriLankanOffset = 5.5 * 60 * 60 * 1000
  const sriLankanDate = new Date(date.getTime() + sriLankanOffset)

  return sriLankanDate.toISOString().split('T')[0]
}

const container = document.getElementById('hot')
let hot

// Custom Renderer for Multi-Select
const multiselectRenderer = function (
  instance,
  td,
  row,
  col,
  prop,
  value,
  cellProperties
) {
  Handsontable.renderers.TextRenderer.apply(this, arguments)

  const items = value ? value.split(', ') : []
  td.innerHTML = items.join(', ')
}

// Custom Editor for Multi-Select
const multiselectEditor = Handsontable.editors.TextEditor.prototype.extend()

multiselectEditor.prototype.createElements = function () {
  Handsontable.editors.TextEditor.prototype.createElements.apply(
    this,
    arguments
  )

  const dropdown = document.createElement('div')
  dropdown.className = 'multiselect-dropdown'

  const dropdownList = document.createElement('div')
  dropdownList.className = 'multiselect-dropdown-list'

  const options = ['INV', 'GIN', 'GRN', 'WARRANTY GIN', 'WARRANTY GRN']

  options.forEach(option => {
    const label = document.createElement('label')
    const checkbox = document.createElement('input')
    checkbox.type = 'checkbox'
    checkbox.value = option

    label.appendChild(checkbox)
    label.appendChild(document.createTextNode(option))

    dropdownList.appendChild(label)
  })

  dropdown.appendChild(dropdownList)
  this.TEXTAREA_PARENT.appendChild(dropdown)

  this.dropdownList = dropdownList
  this.dropdown = dropdown
}

multiselectEditor.prototype.open = function () {
  Handsontable.editors.TextEditor.prototype.open.apply(this, arguments)
  this.dropdownList.classList.add('show')
  const value = this.originalValue || ''
  const items = value.split(', ')

  Array.from(
    this.dropdownList.querySelectorAll('input[type="checkbox"]')
  ).forEach(checkbox => {
    checkbox.checked = items.includes(checkbox.value)
  })
}

multiselectEditor.prototype.close = function () {
  this.dropdownList.classList.remove('show')
  Handsontable.editors.TextEditor.prototype.close.apply(this, arguments)
}

multiselectEditor.prototype.getValue = function () {
  const checkedItems = Array.from(
    this.dropdownList.querySelectorAll('input[type="checkbox"]:checked')
  )
  return checkedItems.map(item => item.value).join(', ')
}

// Initialize Handsontable
document.addEventListener('DOMContentLoaded', function () {
  // Retrieve saved column widths from local storage
  var savedColumnWidths = 50
  // console.log('savedColumnWidths', savedColumnWidths)
  if (savedColumnWidths < 0 || savedColumnWidths == 'undefined') {
    savedColumnWidths = 50
  } else {
    const savedColumnWidths = localStorage.getItem('columnWidths')
  }
  const columnWidths = savedColumnWidths ? JSON.parse(savedColumnWidths) : []
  let selectedRows = new Set()

  hot = new Handsontable(container, {
    colHeaders: [
      'INV',
      'GRN',
      'GIN',
      'L-INV',
      'CUSTOMER',
      'ITEM CODE',
      'SERIAL',
      'QTY',
      'VALUE',
      'VAT',
      'GP',
      'REP',
      'WARRANTY',
      'DESCRIPTION',
      'MEMO',
      'SALESREP',
      'OBJECTIVE',
      'STATUS',
      'DATE'
    ],
    columns: [
      {
        data: 'inv'
      },
      {
        data: 'grn'
      },
      {
        data: 'gin'
      },
      {
        data: 'lInvNo'
      },
      {
        data: 'customer'
      },
      {
        data: 'itemCode'
      },
      {
        data: 'serial'
      },
      {
        data: 'qty'
      },
      {
        data: 'value',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00'
        }
      }, // VALUE column
      {
        data: 'vat',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00'
        }
      }, // VAT column
      {
        data: 'gp',
        type: 'numeric',
        numericFormat: {
          pattern: '0,0.00'
        }
      }, // GP column
      {
        data: 'rep'
      },
      {
        data: 'warranty'
      },

      {
        data: 'itemDescription'
      },
      {
        data: 'memo'
      },
      {
        data: 'cusEmployee'
      },
      {
        data: 'object'
      },
      {
        data: 'status',
        editor: multiselectEditor,
        renderer: multiselectRenderer
      },
      {
        data: 'date',
        type: 'date',
        dateFormat: 'YYYY-MM-DD',
        correctFormat: true
      }
    ],
    manualColumnResize: true,
    columnHeader: true,
    colWidths: columnWidths, // Apply saved column widths
    rowHeaders: true,
    width: '100%',
    height: 500,
    stretchH: 'all',
    filters: true,
    dropdownMenu: true,
    columnSorting: true,
    // Fix the headers
    contextMenu: {
      items: {
        view: {
          name: 'View Details',
          callback: function (key, selection) {
            // Check if selection exists
            if (selection && selection.length > 0) {
              const selectedColumn = selection[0].start.col // Get selected column index
              const selectedRow = selection[0].start.row // Get selected row index
              const columnHeader = hot.getColHeader(selectedColumn) // Get column header
              const cellContent = hot.getDataAtCell(selectedRow, selectedColumn) // Get cell content
              const invCellContent = hot.getDataAtCell(selectedRow, 0)

              if (cellContent && cellContent != 'null') {
                if (columnHeader == 'INV') {
                  window.location.href = `../../pages/dashboards/invoice_new.php?id=${cellContent}`
                } else if (columnHeader == 'GRN') {
                  window.location.href = `../../pages/dashboards/grn.php?id=${cellContent}`
                } else if (columnHeader == 'GIN') {
                  window.location.href = `../../pages/dashboards/gin.php?id=${cellContent}`
                }
              } else {
                console.log('The field is empty')
                if (
                  confirm(
                    `Fool...! You cannot view the details of empty columns. It's ok, You can add new ${columnHeader} to this invoice number. Continue..?`
                  )
                ) {
                  if (columnHeader == 'INV') {
                    window.location.href = `../../pages/dashboards/invoice_new.php?invNo=${invCellContent}`
                  } else if (columnHeader == 'GRN') {
                    window.location.href = `../../pages/dashboards/grn.php?invNo=${invCellContent}`
                  } else if (columnHeader == 'GIN') {
                    window.location.href = `../../pages/dashboards/gin.php?code=${invCellContent}`
                  }
                }
              }
            } else {
              console.error('No selection available.')
            }
          }
        },
        remove_row: {
          name: 'Remove row',
          callback: function (key, selection) {
            if (selection && selection.length > 0) {
              const selectedRow = selection[0].start.row
              const removedData = hot.getSourceDataAtRow(selectedRow) // Get row data before deletion

              if (!removedData || !removedData.id) {
                console.error('Invalid row data:', removedData)
                Swal.fire(
                  'Error!',
                  'Failed to fetch row data before deletion.',
                  'error'
                )
                return
              }

              Swal.fire({
                title: 'Are you sure?',
                text: `Are you sure you want to remove row ${selectedRow + 1}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
              }).then(result => {
                if (result.isConfirmed) {
                  deleteRow(selectedRow, removedData) // Delete from DB first
                  hot.alter('remove_row', selectedRow) // Then remove from UI

                  Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'The row has been removed.',
                    showConfirmButton: false,
                    toast: true,
                    position: 'bottom'
                  })
                }
              })
            } else {
              console.error('No row selected.')
            }
          }
        }
      }
    },

    licenseKey: 'non-commercial-and-evaluation',
    beforeOnCellMouseDown: function (event, coords) {
      if (coords.row < 0) {
        // Clicked on header
        return
      }
      if (event.shiftKey) {
        // Handle shift+click selection
        const lastSelected = Array.from(selectedRows).pop()
        const start = Math.min(lastSelected || coords.row, coords.row)
        const end = Math.max(lastSelected || coords.row, coords.row)
        for (let i = start; i <= end; i++) {
          selectedRows.add(i)
        }
      } else if (event.ctrlKey || event.metaKey) {
        // Handle ctrl/cmd+click selection
        if (selectedRows.has(coords.row)) {
          selectedRows.delete(coords.row)
        } else {
          selectedRows.add(coords.row)
        }
      } else {
        // Regular click
        selectedRows.clear()
        selectedRows.add(coords.row)
      }
      // console.log('Selected rows:', Array.from(selectedRows));
    },
    afterSelection: function (r, c, r2, c2) {
      const selection = hot.getSelected() || []
      selectedRows.clear()
      selection.forEach(([startRow, startCol, endRow, endCol]) => {
        for (let row = startRow; row <= endRow; row++) {
          selectedRows.add(row)
        }
      })
      //  console.log('Selected rows:', Array.from(selectedRows))
    },
    afterChange: function (changes, source) {
      if (source !== 'loadData' && changes) {
        changes.forEach(([row, prop, oldValue, newValue]) => {
          const rowData = hot.getSourceDataAtRow(row)
          saveData(rowData)
        })
      }
    },
    afterCreateRow: function (index, amount, source) {
      if (
        source === 'ContextMenu.rowBelow' ||
        source === 'ContextMenu.rowAbove' ||
        source === 'button'
      ) {
        const rowData = hot.getSourceDataAtRow(index)
        saveData(rowData)
      }
    },
    afterRemoveRow: function (index, amount, physicalRows) {
      const removedData = physicalRows.map(row => hot.getSourceDataAtRow(row))
      console.log('Rows removed:', removedData)

      // No need to handle deletion here since it's handled in the context menu callback
    },
    afterRender: function () {
      // Ensure new rows have checkboxes correctly created
      document.querySelectorAll('.multiselect-dropdown').forEach(dropdown => {
        if (!dropdown.classList.contains('initialized')) {
          dropdown.classList.add('initialized')
          const dropdownList = dropdown.querySelector(
            '.multiselect-dropdown-list'
          )
          const options = ['INV', 'GIN', 'GRN', 'WARRANTY GIN', 'WARRANTY GRN']

          options.forEach(option => {
            const label = document.createElement('label')
            const checkbox = document.createElement('input')
            checkbox.type = 'checkbox'
            checkbox.value = option

            label.appendChild(checkbox)
            label.appendChild(document.createTextNode(option))

            dropdownList.appendChild(label)
          })
        }
      })
    },
    afterColumnResize: function (currentColumn, newSize, column) {
      // Save column widths to local storage
      const columnWidths = hot.getColWidth()
      localStorage.setItem('columnWidths', JSON.stringify(columnWidths))
    }
  })

  $('#generateChart').on('click', function () {
    if (selectedRows.size === 0) {
      alert('Please select at least one row to generate a chart.')
      return
    }

    const labels = []
    const values = []
    const vats = []
    const gps = []

    // Collect data from selected rows
    Array.from(selectedRows).forEach(rowIndex => {
      const rowData = hot.getSourceDataAtRow(rowIndex)
      labels.push(rowData.inv) // Using INV as label
      values.push(parseFloat(rowData.value) || 0)
      vats.push(parseFloat(rowData.vat) || 0)
      gps.push(parseFloat(rowData.gp) || 0)
    })

    // Clear previous chart if it exists
    if (window.myChart instanceof Chart) {
      window.myChart.destroy()
    }

    const ctx = document.getElementById('myChart').getContext('2d')
    window.myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Value',
            data: values,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          },
          {
            label: 'VAT',
            data: vats,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
          },
          {
            label: 'GP',
            data: gps,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    })
  })

  document.getElementById('addRow').addEventListener('click', function () {
    hot.alter('insert_row')
  })

  function saveData (rowData) {
    const formData = new FormData()
    Object.entries(rowData).forEach(([key, value]) => {
      formData.append(key, value)
    })

    Swal.fire({
      icon: 'info',
      title: 'Edit Data',
      text: 'You cannot edit data here. To Edit,   Right-click the INV/GRN/GIN of the data and click "View Details" to edit the data.',
      toast: true,
      showConfirmButton: false,
      position: 'bottom'
    })

    // fetch('../../functions/updateMasterInv.php', {
    //   method: 'POST',
    //   body: formData
    // })
    //   .then(response => response.json())
    //   .then(data => {
    //   if (data.messages && data.messages.length > 0) {
    //     console.log('data', data);
    //     setTimeout(() => {
    //     Swal.fire({
    //       title: 'Success',
    //       text: data.messages.join(', '),
    //       icon: 'success',
    //       toast: true,
    //       showConfirmButton: false,
    //       position: 'bottom'
    //     });
    //     }, 1000);
    //   } else {
    //     throw new Error('Unexpected response format');
    //   }
    //   })
    //   .catch(error => {
    //   console.error('Error saving data:', error);
    //   Swal.fire({
    //     title: 'Error',
    //     text: 'Failed to save data. Please try again.',
    //     icon: 'error',
    //     toast: true,
    //     showConfirmButton: false,
    //     position: 'bottom'
    //   });
    //   });
  }

  function deleteRow (row, rowData) {
    deleteData(rowData.id) // Assuming 'inv' is your primary key column name
  }

  function deleteData (id) {
    const formData = new FormData()
    formData.append('inv', id)
    formData.append('table', 'masterinvoice')
    formData.append('primaryKey', 'id')

    fetch('../../functions/publicDelete.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.text())
      .then(data => {
        console.log('Delete Response:', data)
      })
      .catch(error => {
        console.error('Error deleting row:', error)
      })
  }

  // Fetch data from the PHP script and load it into Handsontable
  fetch('../../functions/fetchMasterInv.php')
    .then(response => response.json())
    .then(data => {
      const loadingScreen = document.getElementById('loadingScreen')
      loadingScreen.style.display = 'flex'
      setTimeout(() => {
        data.sort((a, b) => new Date(b.date) - new Date(a.date))
        hot.loadData(data)
        loadingScreen.style.display = 'none'
      }, 1000)
    })
    .catch(error => console.error('Error fetching data:', error))

  // Store the original data once when the page loads
  let originalData = []

  // Fetch data from the PHP script and load it into Handsontable
  fetch('../../functions/fetchMasterInv.php')
    .then(response => response.json())
    .then(data => {
      data.sort((a, b) => new Date(b.date) - new Date(a.date))
      setTimeout(() => {
        originalData = data // Save original data for reference
        hot.loadData(data)
        loadingScreen.style.display = 'none'
      }, 1000)
    })
    .catch(error => console.error('Error fetching data:', error))

  // Event listener for search input
  document.getElementById('search').addEventListener('input', function () {
    const searchValue = this.value.toLowerCase().trim() // Get the lowercase, trimmed value of the input

    // Filter the data based on search input across all columns
    const filteredData = originalData.filter(row => {
      // If search is empty, display all data
      if (searchValue === '') return true

      // Otherwise, check if any cell in the row matches the search term
      return Object.values(row).some(
        value => value && value.toString().toLowerCase().includes(searchValue)
      )
    })

    // Update Handsontable with the filtered data
    hot.loadData(filteredData)
  })
})
