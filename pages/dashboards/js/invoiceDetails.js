document.addEventListener('DOMContentLoaded', function () {
  fetchInvoices()
  setupFilters()

  // Filter Event Listeners
  document.getElementById('searchBar').addEventListener('input', filterTable)
  document.getElementById('filterRep').addEventListener('change', filterTable)
  document.getElementById('filterDate').addEventListener('change', filterTable)
})

function fetchInvoices () {
  fetch('../../functions/Invoice-api.php')
    .then(response => response.json())
    .then(data => {
      if (data.success === 'success') {
        populateTable(data.data)
      } else {
        document.getElementById('invoiceTableBody').innerHTML =
          "<tr><td colspan='10' class='text-center'>No data available</td></tr>"
      }
    })
    .catch(error => {
      console.error('Error:', error)
      document.getElementById('invoiceTableBody').innerHTML =
        "<tr><td colspan='10' class='text-center'>Failed to fetch data</td></tr>"
    })
}

function populateTable (invoices) {
  let tableBody = document.getElementById('invoiceTableBody')
  tableBody.innerHTML = ''

  let invoiceMap = new Map() // Store invoices grouped by invoice #

  invoices.forEach(invoice => {
    if (!invoiceMap.has(invoice.inv)) {
      invoiceMap.set(invoice.inv, {
        info: invoice,
        items: []
      })
    }
    invoiceMap.get(invoice.inv).items.push(invoice)
  })

  let rows = ''
  invoiceMap.forEach((invoiceData, inv) => {
    let invoice = invoiceData.info
    rows += `
            <tr>
            <td>${invoice.inv}</td>
            <td>${invoice.customer}</td>
            <td>${invoice.inv_date}</td>
            <td>${invoice.po_num}</td>
            <td>${invoice.rep}</td>
            <td>${invoice.terms}</td>
            <td>${invoice.shipping_date}</td>
            <td>${invoice.vat}%</td>
            <td>${invoice.discountValue} (${invoice.discountStatus})</td>
            <td class="d-flex flex-row justify-content-between align-content-center gap-1"> 
            <button class="btn btn-primary text-xs btn-view" onclick="toggleItems('${inv}')" title="View Items">
                <i class="material-icons" style="font-size: 16px;">visibility</i>
            </button>
            <button class="btn btn-primary text-sm btn-edit" onclick="editInvoice('${inv}')" title="Edit Invoice">
                <i class="material-icons" style="font-size: 16px;">edit</i>
            </button>
            </td>
            </tr>
            <tr class="invoice-items" id="items-${inv}">
            <td colspan="10">
            <table class="table table-sm table-bordered">
            <thead class="table-info">
                <tr>
                <th>Item Code</th>
                <th>Quantity</th>
                <th>Serials</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>VAT</th>
                <th>Warranty</th>
                </tr>
            </thead>
            <tbody>
                ${invoiceData.items
                  .map(
                    item => `
                <tr>
                <td>${item.item_code}</td>
                <td>${item.qt}</td>
                <td>${item.serials}</td>
                <td>${item.description}</td>
                <td>${item.unit_price}</td>
                <td>${item.total}</td>
                <td>${item.item_vat}%</td>
                <td>${item.warranty}</td>
                </tr>
                `
                  )
                  .join('')}
            </tbody>
            </table>
            </td>
            </tr>
        `
  })
  tableBody.innerHTML = rows

  // Populate Rep Filter dropdown
  let reps = [...new Set(invoices.map(invoice => invoice.rep))]
  let repSelect = document.getElementById('filterRep')
  reps.forEach(rep => {
    let option = document.createElement('option')
    option.value = rep
    option.innerText = rep
    repSelect.appendChild(option)
  })
}

function toggleItems (inv) {
  let row = document.getElementById(`items-${inv}`)
  row.style.display =
    row.style.display === 'none' || row.style.display === ''
      ? 'table-row'
      : 'none'
}

function filterTable () {
    let searchValue = document.getElementById('searchBar').value.toLowerCase()
    let repFilter = document.getElementById('filterRep').value.toLowerCase()
    let dateFilter = document.getElementById('filterDate').value
  
    let rows = document.querySelectorAll('#invoiceTableBody tr')
  
    rows.forEach(row => {
      // Skip the row if it's the item details row (those rows are grouped with invoices)
      if (row.classList.contains('invoice-items')) return;
    
      let invoiceNumber = row.querySelector('td:nth-child(1)').innerText.toLowerCase()
      let customer = row.querySelector('td:nth-child(2)').innerText.toLowerCase()
      let rep = row.querySelector('td:nth-child(5)').innerText.toLowerCase()
      let date = row.querySelector('td:nth-child(3)').innerText
    
      // Default match flag for current row
      let match = false
    
      // Match invoice number or customer with search term
      if (invoiceNumber.includes(searchValue) || customer.includes(searchValue)) {
        match = true
      }
    
      // Apply Rep filter if selected
      if (repFilter && !rep.includes(repFilter)) {
        match = false
      }
    
      // Apply Date filter if selected
      if (dateFilter && date !== dateFilter) {
        match = false
      }
    
      // Show or hide the row based on match
      row.style.display = match ? '' : 'none'
    
      // Show or hide the item details row based on the visibility of the invoice row
      let itemRow = document.getElementById(`items-${invoiceNumber}`)
      if (itemRow) {
        itemRow.style.display = match ? 'table-row' : 'none'
      }
    })
  }
  

function setupFilters () {
  let today = new Date().toISOString().split('T')[0]
  document.getElementById('filterDate').setAttribute('max', today)
}
