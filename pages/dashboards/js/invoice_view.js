document.addEventListener('DOMContentLoaded', function () {
  // Create a new URLSearchParams object using the query string from the URL
  const urlParams = new URLSearchParams(window.location.search)
  // Get the value of the 'id' query parameter
  const urlID = urlParams.get('id')
  if (urlID) {
    // console.log(urlID); // This will log the value of the 'id' parameter from the URL
    fetchInvoice(urlID)
    //  fetchInvoiceItems();
  } else {
    // console.log('URL value is not set')
    const message = 'URL value is not set'
    const background = 'bg-danger'
    showToast(message, background)
    // Delay the redirection by 15 seconds (15000 milliseconds)
    setTimeout(() => {
      if (document.referrer) {
        location.href = document.referrer // Redirect to the previous page
      } else {
        location.href = 'invoice.php' // Fallback to a default page
      }
    }, 3000) // 15-second delay
  }
})

function fetchInvoice (urlID) {
  fetch('../../functions/fetchInvoice.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Error while fetchin g data')
      }
      return response.json()
    })
    .then(response => {
        // console.log(response)
      fetchInvoiceItems(urlID, response)
    })
    .catch(error => {
      console.log('Error occured while fetching data', error)
    })
}

function fetchInvoiceItems (urlID, generalItems) {
  //console.log('generalItems',generalItems);

  fetch('../../functions/fetchInvoiceItems.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('Error in fetching process')
      }
      return response.json()
    })
    .then(response => {
      // console.log('fetched Items',response);
      populateData(urlID, generalItems, response)
    })
    .catch(error => {
      console.log('Error Occured with fetching inv items')
    })
}

// Function to insert a line break after every 50 characters, ensuring it's after a space
function formatDescription (description, maxLineLength = 50) {
  let formattedDescription = ''
  let words = description.split(' ') // Split description by spaces
  let line = ''

  words.forEach(word => {
    if ((line + word).length <= maxLineLength) {
      line += word + ' '
    } else {
      formattedDescription += line.trim() + '<br>' // Add line break after 50 characters
      line = word + ' ' // Start new line
    }
  })

  // Append the remaining words to the description
  formattedDescription += line.trim()

  return formattedDescription
}

function populateData (urlID, generalItems, items) {
  // console.log('urlID', urlID);
  // console.log('generalItems', generalItems);
  // console.log('items', items);
  // Filter generalItems where the id matches urlID
  const filteredGeneralData = generalItems.filter(item => item.inv == urlID)
  // Filter items where inv_no matches urlID
  let vatStatus
  let discount
  let discountStatus
  let invFk
  let discountValue
  // console.log('filteredGeneralData', filteredGeneralData);
  filteredGeneralData.forEach(genItem => {
    vatStatus = genItem.vat
    discount = genItem.discountValue
    discountStatus = genItem.discountStatus
    invFk = genItem.id
    discountValue = genItem.discountValue
  })

  const filteredItems = items.filter(item => item.inv_no == invFk)
  const valuedFilteredItems = filteredItems.filter(item => item.unit_price > 1)
  const valuedFilteredItemsCount = valuedFilteredItems.length


  if (filteredItems.length > 0) {
    const tbody = document.querySelector('#main-items tbody')
    tbody.innerHTML = '' // Clear existing rows

    let subtotalValue = 0 // Initialize subtotal
    let fetchedVat = 0
    let subtotal = document.getElementById('subtotal')
    let discountElemnt = document.getElementById('discount')
    let vat = document.getElementById('vat')
    let total = document.getElementById('total')
    const itemCount = filteredItems.length
    unitDiscount = parseFloat(discount) / itemCount

    filteredItems.forEach(item => {
      const row = document.createElement('tr')
      let quantity = item.qt
      let itemCode = item.item_code
      let description = item.description
      let unitPrice
      let rowTotal

      // console.log('vatStatus',vatStatus);
      if (vatStatus == 'yes') {

        unitPrice = parseFloat(item.unit_price)
        rowTotal = item.total
        
        if (discountStatus == 'hide') {
        //   console.log('Befor unitPrice', unitPrice);

          document.getElementById('discountArea').hidden = true
          let distribuedDiscount = parseFloat(discountValue) / valuedFilteredItemsCount

          if(unitPrice && unitPrice > 1){
            console.log(unitPrice ,' - ', distribuedDiscount);
            unitPrice -= distribuedDiscount;
          }
        } else {
          discountElemnt.textContent = discount
        }

        rowTotal = unitPrice * quantity
        subtotalValue += parseFloat(rowTotal)

        // Sum up the total value for each item
        subtotalValue += parseFloat(item.total)
        fetchedVat = parseFloat(item.vat) / 100
        // Assign the calculated subtotal to the respective element
        subtotal.textContent = formatCurrency(subtotalValue) // Ensure 2 decimal places
        // You can also calculate VAT and total here if needed
        let vatValue = subtotalValue * fetchedVat // For example, 15% VAT
        let totalValue = subtotalValue + vatValue
        vat.textContent = formatCurrency(vatValue) // Assign VAT value
        total.textContent = formatCurrency(totalValue) // Assign total value
      } else if (vatStatus == 'no') {
       
        rowVat = parseFloat(item.unit_price) * (parseFloat(item.vat) / 100)
        unitPrice = parseFloat(item.unit_price) + rowVat
        rowTotal = unitPrice * quantity
        subtotalValue += parseFloat(rowTotal)
        discount.value = item.discount
        total.textContent = formatCurrency(subtotalValue) // Assign total value
      }

      row.innerHTML = `
                <td style="border-right: 1px solid #000 !important;">${quantity}</td>
                <td style="border-right: 1px solid #000 !important;">${itemCode}</td>
                <td style="border-right: 1px solid #000 !important;">${description.replace(
                  /\n/g,
                  '<br>'
                )}</td>
                <td style="border-right: 1px solid #000 !important; text-align:end; ">${formatCurrency(
                  unitPrice
                )}</td>
                <td class="text-end" >${formatCurrency(rowTotal)}</td>
            `
      tbody.appendChild(row)

      // console.log('fetchedVat',fetchedVat);
    })
  }

  if (filteredGeneralData.length > 0) {
    setTimeout(() => {
        fetchCustomerDetails(filteredGeneralData[0].customer).then((data) => {
            console.log('customer', data);
            if (data.success) {
                const billToElement = document.getElementById('billTo')
                billToElement.innerHTML = `
                    ${data.company}<br>
                    ${data.address}
                `
            } else {
                const message = 'Customer not found'
                const background = 'bg-danger'
                const icon = 'fa fa-exclamation-circle'
                showToast(message, background, icon)
            }
        });
    }, 1000);
    // console.log('filteredGeneralData', filteredGeneralData);
    const billToElement = document.getElementById('billTo')
    billToElement.innerHTML = ''

    const date = document.getElementById('date')
    date.innerHTML = filteredGeneralData[0].inv_date || ''

    const invNo = document.getElementById('invNo')
    invNo.innerHTML = filteredGeneralData[0].inv || ''

    const poNo = document.getElementById('poNo')
    poNo.innerHTML = filteredGeneralData[0].po_num || ''

    const terms = document.getElementById('terms')
    terms.innerHTML = filteredGeneralData[0].terms || ''

    const rep = document.getElementById('rep')
    rep.innerHTML = filteredGeneralData[0].rep || ''

    const shipDate = document.getElementById('shipDate')
    shipDate.innerHTML = filteredGeneralData[0].shipping_date || ''
  } else {
    message = 'There is no item to display..'
    background = 'bg-danger'
    showToast(message, background)
    window.location.href = './invoice_new.php'
  }
}

function formatCurrency (value) {
  return parseFloat(value).toLocaleString('en-US', {
    style: 'decimal',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

// Function to show toast
function showToast (message, background) {
  // Set the toast message dynamically
  const toastBody = document.querySelector('#liveToast .toast-body')
  toastBody.textContent = message
  toastBody.classList.add(background) // Add the background class passed as a parameter

  // Display the overlay and blur the background
  document.getElementById('toastOverlay').classList.remove('d-none')
  document.getElementById('toastContainer').classList.remove('d-none')
  document.body.classList.add('blurred')

  // Initialize and show the toast
  const toastElement = document.getElementById('liveToast')
  const toastBootstrap = new bootstrap.Toast(toastElement)

  toastBootstrap.show()

  // When toast is hidden, remove the overlay and restore page
  toastElement.addEventListener('hidden.bs.toast', () => {
    document.getElementById('toastOverlay').classList.add('d-none')
    document.getElementById('toastContainer').classList.add('d-none')
    document.body.classList.remove('blurred')
  })
}

const fetchCustomerDetails = async (cusName) => {
    // console.log('cusName', cusName);
    try {
        const response = await fetch('../../functions/fetchCustomers.php');
        if (!response.ok) {
            throw new Error('Error in fetching process');
        }
        const customers = await response.json();
        const customerList = Array.isArray(customers.data) ? customers.data : customers;

        // Log all available company names
        // console.log('Available company names:', customerList.map(c => c.company_name));

        // Normalize function
        const normalizeString = (str) => str.replace(/\s+/g, ' ').trim().toLowerCase();

        // Find customer
        const customer = customerList.find(customer => 
            customer.company_name && normalizeString(customer.company_name) === normalizeString(cusName)
        );

        if (customer) {
            // console.log('customer name:', customer);
            return {
                success : true,
                company: customer.company_name,
                address: customer.address,
            };
        } else {
            // console.log('Customer not found');
            return {
                success: false,
                message: 'Customer not found',
            };
        }
    } catch (error) {
        console.log('Error Occurred with fetching customer details', error);
        return '';
    }
};
