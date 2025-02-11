// front end part

// Declare global variables
let crmData = null
let crmItems = null
let users = null
let models = null
let brand = null
let customers = null

// Async function to fetch data
async function fetchData () {
  const loadingScreen = document.getElementById('loadingScreen')
  loadingScreen.style.display = 'flex'

  try {
    const minLoadingTime = new Promise(resolve => setTimeout(resolve, 500))

    // Fetch CRM Data
    const crmDataResponse = await fetch('../../functions/fetchCrmData.php')
    if (!crmDataResponse.ok) throw new Error('Error while fetching CRM Data')
    const fetchedCrmData = await crmDataResponse.json()

    // Fetch CRM Items
    const crmItemsResponse = await fetch('../../functions/fetchCrmItems.php')
    if (!crmItemsResponse.ok) throw new Error('Error while fetching CRM Items')
    const fetchedCrmItems = await crmItemsResponse.json()

    // Get the last item and log its id
    const lastItemId = fetchedCrmItems[fetchedCrmItems.length - 1]?.id || 0
    document.getElementById('id').value = Number(lastItemId) + 1

    await Promise.all([minLoadingTime, crmDataResponse, crmItemsResponse])

    populatePartnerLoockup(fetchedCrmData)
    commonSearch(fetchedCrmData)

    if (userLevel === 'admin') {
      // Admin: No filtering, use all data
      crmData = fetchedCrmData // Update the global variable
      crmItems = fetchedCrmItems // Update the global variable
      crmItems.forEach(item => {
        const relatedCrmData = crmData.find(data => data.id === item.trnNo)
        if (relatedCrmData) {
          item.description = relatedCrmData.description
          item.stage = relatedCrmData.stage
          item.customer = relatedCrmData.customer
          item.partner = relatedCrmData.partner
        }
      })
      crmData.forEach(data => {
        const relatedCrmItems = crmItems.filter(item => item.trnNo === data.id)
        if (relatedCrmItems.length > 0) {
          data.followUp = relatedCrmItems.map(item => item.followUp)
        }
      })
    } else {
      // Non-admin: Filter data by session user
      const matchedSalesRep = fetchedCrmData.filter(
        item => item.salesRep.trim() === userSession.trim()
      )

      if (matchedSalesRep.length > 0) {
        //update table if user is sales rep

        crmData = matchedSalesRep // Update the global variable
        const matchedFupUser = fetchedCrmItems.filter(
          item => item.salesRep.trim() === userSession.trim()
        )
        crmItems = matchedFupUser // Update the global variable

        crmItems.forEach(item => {
          const relatedCrmData = crmData.find(data => data.id === item.trnNo)
          if (relatedCrmData) {
            item.description = relatedCrmData.description
            item.stage = relatedCrmData.stage
            item.customer = relatedCrmData.customer
            item.partner = relatedCrmData.partner
          }
        })

        crmData.forEach(data => {
          const relatedCrmItems = crmItems.filter(
            item => item.trnNo === data.id
          )
          if (relatedCrmItems.length > 0) {
            data.followUp = relatedCrmItems.map(item => item.followUp)
          }
        })
      } else {
        //update table if user is follow up user
        const matchedFupUserData = fetchedCrmItems.filter(
          item => item.fupUser.trim() === userSession.trim()
        )

        const matchedFupUserDataWithDetails = matchedFupUserData
          .map(item => {
            const relatedCrmData = fetchedCrmData.find(
              data => data.id === item.trnNo
            )
            return relatedCrmData
          })
          .filter(data => data !== undefined)

        crmData = matchedFupUserDataWithDetails
        crmItems = fetchedCrmItems.filter(item =>
          matchedFupUserDataWithDetails.some(data => data.id === item.trnNo)
        )
      }
    }

    //Fetch Rep Details
    const usersResponse = await fetch('../../functions/fetchUsers.php')
    if (!usersResponse.ok) throw new Error('Error while fetching Rep Items')
    users = await usersResponse.json()

    // Fetch product Details
    const productResponse = await fetch('../../functions/fetchProducts.php')
    if (!productResponse.ok) throw new Error('Error while fetching product ')
    products = await productResponse.json()

    // Fetch brand Details
    const brandResponse = await fetch('../../functions/fetchBrand.php')
    if (!brandResponse.ok) throw new Error('Error while fetching brand ')
    brand = await brandResponse.json()
    // Fetch brand Details
    if (crmData || crmItems) {
      populateCustomers()
      populatePartners()
      populateBrand(brand)
      populateModels(products)
      populateUsers(users)
      populateDashBoard(crmItems, crmData)
      calculateAndDisplayTotals(crmData)
      populateGp(crmItems)
      populateDealNumber(crmData)
      dailyPerformanceDeal(crmData)
      dailyPerformanceDealActions(crmItems)
      linkCards(crmData, crmItems, users)
      // Access the data later
      setTimeout(() => {
        //  //console.log('Accessing crmData globally:', crmData);
        //  //console.log('Accessing crmItems globally:', crmItems);
        populateCrmData(crmData, crmItems, users)
        populateCrmItems(crmItems, crmData, users)
      }, 1000) // Ensures enough time for fetch to complete
    }
  } catch (error) {
    console.error('Error fetching data:', error)
  } finally {
    setTimeout(() => {
      loadingScreen.style.display = 'none'
    }, 1000)
    // Hide the loading screen
  }
}

// Call the function to fetch and assign data
fetchData()

function refreshCrmItems () {
  populateCrmItems(crmItems, crmData)
}

function refreshCrmData () {
  populateCrmData(crmData, crmItems)
}

async function populateCustomers () {
  // console.log('fetching customer data');
  try {
    const customerResponse = await fetch('../../functions/fetchCustomers.php')
    if (!customerResponse.ok) throw new Error('Error while fetching customers')
    const customers = await customerResponse.json()
    const selectElement = document.getElementById('dealCustomer')

    // Clear existing options
    selectElement.innerHTML = '<option value="">SELECT</option>'

    // Filter customers by type "end_customer"
    const filteredCustomers = customers.filter(
      item => item.type === 'end_customer'
    )

    // Add filtered customers to the select element
    filteredCustomers.forEach(item => {
      const option = document.createElement('option')
      option.value = item.company_name.trim() // Ensure no extra spaces
      option.textContent = item.company_name
      selectElement.appendChild(option)
    })

    // Initialize Select2
    $('#dealCustomer').select2({
      placeholder: 'Select Customer',
      allowClear: true,
      width: '100%',
      dropdownParent: $('#mainTableModal'), // Fixes dropdown positioning
      language: {
        noResults: function () {
          // Custom "Add Customer" button
          return $('<button>')
            .addClass('btn add-customer-btn')
            .text('Add Customer')
            .on('click', function (e) {
              e.preventDefault() // Prevent default behavior
              $('#customerModal').modal('show') // Show the modal
            })
        }
      },
      escapeMarkup: function (markup) {
        return markup // Allow rendering custom HTML
      }
    })
  } catch (error) {
    console.error('An error occurred:', error.message)
  }
}

var selectedCustomerName

async function populatePartners () {
  // console.log('fetching Partner data');

  try {
    const customerResponse = await fetch('../../functions/fetchCustomers.php')
    if (!customerResponse.ok) throw new Error('Error while fetching customers')
    const customers = await customerResponse.json()
    const selectElement = document.getElementById('dealPartner')

    // Clear existing options
    selectElement.innerHTML = '<option value="">SELECT</option>'

    // Filter customers by type "partner"
    const filteredCustomers = customers.filter(item => item.type === 'partner')

    // Add filtered customers to the select element
    filteredCustomers.forEach(item => {
      const option = document.createElement('option')
      option.value = item.company_name // Use customer_id as the option value
      option.textContent = item.company_name.trim()
      selectElement.appendChild(option)
    })

    // Initialize Select2
    $('#dealPartner').select2({
      placeholder: 'Select Partner',
      allowClear: true,
      width: '100%',
      height: '100%',
      dropdownParent: $('#mainTableModal'), // Fixes dropdown positioning
      language: {
        noResults: function () {
          // Custom "Add Partner" button
          return $('<button>')
            .addClass('btn add-customer-btn')
            .text('Add Partner')
            .on('click', function (e) {
              e.preventDefault() // Prevent default behavior
              $('#customerModal').modal('show') // Show the modal
            })
        }
      },
      escapeMarkup: function (markup) {
        return markup // Allow rendering custom HTML
      }
    })

    // Bind change event to the Select2 wrapper
    $('#dealPartner').on('change', function () {
      selectedCustomerName = $(this).val() // Get the selected customer_id
      // console.log('Selected Partner Customer ID:', selectedCustomerName);
      populatePartnerRep()
    })
  } catch (error) {
    console.error('An error occurred:', error.message)
  }
}

function populatePartnerRep (value) {
  if (!value) {
    selectedCompnay = selectedCustomerName
  } else {
    selectedCompnay = value
  }
  $.ajax({
    url: '../../functions/fetchEmployees.php',
    type: 'GET',
    data: { company: selectedCompnay },
    dataType: 'json',
    success: function (response) {
      // console.log('Full Response:', response);

      const selectElement = document.getElementById('dealPartRep')
      if (!selectElement) {
        console.error('Element with ID "dealPartRep" not found!')
        return
      }

      // Clear existing options
      selectElement.innerHTML = '<option value="">SELECT</option>'

      if (response.error) {
        console.warn('Server Error:', response.error)

        // Initialize Select2 with "Add Partner Employee" option
        $('#dealPartRep').select2({
          placeholder: 'Select Partner',
          allowClear: true,
          width: '100%',
          dropdownParent: $('#mainTableModal'), // Fixes dropdown positioning
          language: {
            noResults: function () {
              return $('<button>')
                .addClass('btn add-customer-btn')
                .text('Add Partner Employee')
                .on('click', function (e) {
                  e.preventDefault()
                  document.getElementById('EmplyeeModalcustomer').value =
                    selectedCompnay
                  $('#customerEmployeeModal').modal('show')
                })
            }
          },
          escapeMarkup: function (markup) {
            return markup
          }
        })

        return
      }

      if (!Array.isArray(response.cusEmpName)) {
        console.error('Invalid cusEmpName format:', response.cusEmpName)
        return
      }

      // Add options
      response.cusEmpName.forEach(item => {
        const option = document.createElement('option')
        option.value = item.trim()
        option.textContent = item
        selectElement.appendChild(option)
      })

      // Reinitialize Select2 after adding options
      $('#dealPartRep').select2({
        placeholder: 'Select Partner',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#mainTableModal'),
        language: {
          noResults: function () {
            return $('<button>')
              .addClass('btn add-customer-btn')
              .text('Add Partner Employee')
              .on('click', function (e) {
                e.preventDefault()
                document.getElementById('EmplyeeModalcustomer').value =
                  selectedCompnay
                $('#customerEmployeeModal').modal('show')
              })
          }
        },
        escapeMarkup: function (markup) {
          return markup
        }
      })
    },
    error: function (xhr, status, error) {
      console.error('AJAX error:', status, error)

      // Show "Add Partner Employee" option on AJAX failure
      $('#dealPartRep').select2({
        placeholder: 'Select Partner',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#mainTableModal'),
        language: {
          noResults: function () {
            return $('<button>')
              .addClass('btn add-customer-btn')
              .text('Add Partner Employee')
              .on('click', function (e) {
                e.preventDefault()
                document.getElementById('EmplyeeModalcustomer').value =
                  selectedCompnay
                $('#customerEmployeeModal').modal('show')
              })
          }
        },
        escapeMarkup: function (markup) {
          return markup
        }
      })
    }
  })
}

function populateBrand (brand) {
  const selectElement = document.getElementById('brand')

  // Clear existing options
  selectElement.innerHTML = '<option value="">SELECT</option>'

  // Add brands to the select element
  brand.forEach(item => {
    const option = document.createElement('option')
    option.value = item.brand.trim() // Ensure no extra spaces
    option.textContent = item.brand
    selectElement.appendChild(option)
    //  //console.log('Added option:', item.brand.trim());
  })

  // Initialize Select2
  $(document).ready(function () {
    $('#brand').select2({
      placeholder: 'Select Model',
      allowClear: true,
      width: '100%',
      dropdownParent: $('#detaiTableModal') // Fixes dropdown positioning
    })
  })

  // //console.log($('#brand').length > 0 ? 'Select2 initialized' : 'Select2 not initialized');
  //  //console.log('Dropdown options:', $('#brand').children('option').map((_, el) => $(el).val()).get());
}

function populateModels (products) {
  // //console.log('products', products);
  const selectElement = document.getElementById('model')

  // Clear existing options
  selectElement.innerHTML = '<option value="">SELECT</option>'

  // Add user names to the select element
  products.forEach(product => {
    const option = document.createElement('option')
    option.value = product.item_code // Using id as the value
    option.textContent = product.item_code
    selectElement.appendChild(option)
  })

  // Initialize Select2
  $(document).ready(function () {
    $('#model').select2({
      placeholder: 'Select Model',
      allowClear: true,
      width: '100%',
      dropdownParent: $('#detaiTableModal') // Fixes dropdown positioning
    })
  })
}

function populateUsers (users) {
  const fupUser = document.getElementById('fup')
  const performanceRep = document.getElementById('performanceRep')
  fupUser.innerHTML = '<option value="">SELECT</option>'
  performanceRep.innerHTML = '<option>SELECT</option>'

  // Add user names to the select elements
  users.forEach(user => {
    // Create and append options for the fupUser dropdown
    const fupOption = document.createElement('option')
    fupOption.value = user.user_name
    fupOption.textContent = user.user_name
    fupUser.appendChild(fupOption)

    // Create and append options for the performanceRep dropdown
    const performanceOption = document.createElement('option')
    performanceOption.value = user.user_name
    performanceOption.textContent = user.user_name
    performanceRep.appendChild(performanceOption)
  })

  // Initialize Select2
  $(document).ready(function () {
    $('#fup').select2({
      placeholder: 'Select Engineer',
      width: '100%',
      allowClear: true,
      dropdownParent: $('#detaiTableModal') // Fixes dropdown positioning
    })
  })

  $(document).ready(function () {
    $('#performanceRep').select2({
      placeholder: 'Select Rep',
      width: '100%',
      allowClear: true
    })
  })
}

//-----------------------DashBoard--------------------------------DashBoard-----------------------------DashBoard----------------------------------

function calculateAndDisplayTotals (crmData) {
  // console.log('crmData2', crmData);

  let totalDeals = crmData.length
  let pendingCount = 0
  let completedCount = 0
  let potentialValue = 0

  // Today's date
  const today = new Date().toISOString().split('T')[0]

  // Iterate over each record in crmData
  crmData.forEach(record => {
    // Sort follow-up dates to find the latest
    if (Array.isArray(record.followUp) && record.followUp.length > 0) {
      record.followUp.sort((a, b) => new Date(b) - new Date(a))

      // Get the latest follow-up date
      const latestFollowUp = record.followUp[0]

      // Check the latest follow-up's stage and date
      if (record.stage && record.stage.toLowerCase() === 'completed') {
        completedCount++
      } else if (latestFollowUp && latestFollowUp <= today) {
        pendingCount++
      }
    }

    // Add to potential value
    potentialValue += parseFloat(record.potential || 0)
  })

  // Update Total Deals
  const totalDealsElement = document.getElementById('totalDealsValue')
  if (totalDealsElement) {
    totalDealsElement.textContent = totalDeals
  }

  // Update Potential Value
  const potentialValueElement = document.getElementById('potentialValue')
  if (potentialValueElement) {
    potentialValueElement.textContent = potentialValue.toLocaleString('en-US', {
      style: 'currency',
      currency: 'USD'
    })
  }
}

function populateDashBoard (crmItems) {
  // console.log('crmItems', crmItems);

  const todayItems = []
  const overdueItems = []
  const upcomingItems = []

  // Group items by trnNo
  const groupedItems = crmItems.reduce((acc, item) => {
    if (item.stage === 'completed') return acc // Ignore completed items
    const trnNo = item.trnNo
    if (!acc[trnNo]) acc[trnNo] = []
    acc[trnNo].push(item)
    return acc
  }, {})

  // Get today's date (set to midnight)
  const today = new Date()
  today.setHours(0, 0, 0, 0) // Remove time portion for accurate comparison

  // Process each trnNo group
  Object.keys(groupedItems).forEach(trnNo => {
    const items = groupedItems[trnNo]

    // Filter out invalid followUp dates (e.g., "0000-00-00")
    const validItems = items.filter(item => item.followUp !== '0000-00-00')

    // If no valid followUp dates exist, skip
    if (validItems.length === 0) {
      return
    }

    // Find the latest followUp
    const latestItem = validItems.reduce((latest, current) => {
      const latestDate = new Date(latest.followUp)
      const currentDate = new Date(current.followUp)
      return currentDate > latestDate ? current : latest
    })

    // Parse the latest followUp date
    const followUpDate = new Date(latestItem.followUp)

    // Normalize followUpDate to midnight for accurate comparison
    followUpDate.setHours(0, 0, 0, 0)

    // Categorize the items based on the followUp date
    if (followUpDate.getTime() === today.getTime()) {
      todayItems.push(latestItem) // Push to todayItems array
    } else if (followUpDate < today) {
      overdueItems.push(latestItem) // Push to overdueItems array
    } else {
      upcomingItems.push(latestItem) // Push to upcomingItems array
    }
  })

  // Optional: Log the arrays for confirmation
  // console.log("Today's Follow-ups:", todayItems);
  //  console.log("Overdue Follow-ups:", overdueItems);
  // console.log("Upcoming Follow-ups:", upcomingItems);

  // Return arrays in case you want to use them elsewhere

  // Function to create HTML content for the cards
  function logTrnNo (trnNo) {
    console.log('Selected trnNo:', trnNo)

    if (trnNo) {
      const filteredItems = crmItems.filter(item => item.trnNo === trnNo)

      //console.clear(); // Clear the console for clarity
      console.log('Filtered items for trnNo:', trnNo, filteredItems)

      showDetailTableTab()
      populateCrmItems(filteredItems, crmData)
    } else {
      console.error('No event-card found for the clicked element.')
    }
  }

  const createEventCard = (item, status) => {
    let statusClass = status === 'followUp' ? 'primary' : 'warning'
    let cardClass =
      status === 'followUp'
        ? 'highlight-today'
        : status === 'pending'
        ? 'highlight-past'
        : ''

    return `
        <div class="event-card ${cardClass} p-4 mb-3 bg-white position-relative" 
             data-trnno="${item.trnNo}" 
             data-status="${status}" 
             style="border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);">
            
            <!-- Status Badge -->
            <div class="position-absolute" style="top: 15px; right: 15px;">
                <span class="badge badge-${statusClass} px-3 py-2 rounded-pill">
                    ${status === 'followUp' ? 'Follow Up' : 'Overdue'}
                </span>
            </div>

            <!-- Header Section -->
            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0 me-4 font-weight-bold">#${item.trnNo}</h5>
                <h6 class="mb-0 text-muted">
                    <i class="fas fa-user-tie me-2"></i>${item.salesRep}
                </h6>
            </div>

            <!-- Main Content -->
            <div class="row g-3">
                <!-- Follow Up Date -->
                <div class="col-md-3 col-sm-6">
                    <div class="p-1 rounded bg-light d-flex flex-column" 
                         style="height: 55px; min-width: 200px;">
                        <small class="text-muted">Follow Up Date :</small>
                        <strong class="text-dark">${new Date(
                          item.followUp
                        ).toLocaleDateString()}</strong>
                    </div>
                </div>

                <!-- Customer -->
                <div class="col-md-3 col-sm-6">
                    <div class="p-1 rounded bg-light d-flex flex-column" 
                         style="height: 55px; min-width: 200px;">
                        <small class="text-muted">Customer :</small>
                        <strong class="text-primary text-truncate">${
                          item.customer
                        }</strong>
                    </div>
                </div>

                <!-- Partner -->
                <div class="col-md-3 col-sm-6">
                    <div class="p-1 rounded bg-light d-flex flex-column" 
                         style="height: 55px; min-width: 200px;">
                        <small class="text-muted">Partner :</small>
                        <strong class="text-primary text-truncate">${
                          item.partner
                        }</strong>
                    </div>
                </div>

                <!-- Follow Up Action -->
                <div class="col-md-3 col-sm-6">
                    <div class="p-1 rounded bg-light d-flex flex-column" 
                         style="height: 55px; min-width: 200px;">
                        <small class="text-muted ">Follow Up Action :</small>
                        <strong class="text-dark text-truncate">${
                          item.fupAction
                        }</strong>
                    </div>
                </div>
            </div>
        </div>
    `
  }

  // Attach event listener dynamically
  document.addEventListener('click', event => {
    const card = event.target.closest('.event-card')
    if (card) {
      const trnNo = card.getAttribute('data-trnno')
      logTrnNo(trnNo)
    }
  })

  // Example of using this function with todayItems and overdueItems

  const overdueContainer = document.querySelector('.event-card.pending')
  const todayContainer = document.querySelector('.event-card.followUp')

  // Clear previous content
  overdueContainer.innerHTML = ''
  todayContainer.innerHTML = ''

  // Populate the containers with event cards
  todayItems.forEach(item => {
    todayContainer.innerHTML += createEventCard(item, 'followUp')
  })
  overdueItems.forEach(item => {
    overdueContainer.innerHTML += createEventCard(item, 'pending')
  })

  // Calculate counts
  const followUpCount = todayItems.length // Count of today's follow-ups
  const overdueCount = overdueItems.length // Count of overdue items
  const completedCount = crmItems.filter(
    item => item.stage === 'completed'
  ).length // Count of completed items
  const invoicedDealsCount = crmItems.filter(
    item => item.type === 'Invoice'
  ).length // Count of invoicedDealsCount items
  const quotededDealsCount = crmItems.filter(
    item => item.type === 'Quote'
  ).length // Count of quotededDealsCount items
  const inProgressDealsCount = crmItems.filter(
    item => item.stage === 'in-progress'
  ).length // Count of inProgressDealsCount items
  const continuosDealsCount = crmItems.filter(
    item => item.stage === 'continuous'
  ).length // Count of continuosDealsCount items
  const initialDealsCount = crmItems.filter(
    item => item.stage === 'initial'
  ).length // Count of initialDealsCount items

  // Log the counts to the console
  //console.log("Follow-Up Count:", followUpCount);
  //console.log("Overdue Count:", overdueCount);
  //console.log("Completed Count:", completedCount);

  // Update initialDealsCount
  const initialDealsElement = document.getElementById('initialDeals')
  if (initialDealsElement) {
    initialDealsElement.textContent = initialDealsCount
  }

  // Update continuosDealsCount
  const continuousDealsElement = document.getElementById('continuousDeals')
  if (continuousDealsElement) {
    continuousDealsElement.textContent = continuosDealsCount
  }

  // Update inProgressDealsCount
  const inProgressDealsElement = document.getElementById('inProgressDeals')
  if (inProgressDealsElement) {
    inProgressDealsElement.textContent = inProgressDealsCount
  }

  // Update Pending Follow-ups
  const pendingTotalElement = document.getElementById('pendingTotal')
  if (pendingTotalElement) {
    pendingTotalElement.textContent = overdueCount
  }

  // Update Completed Deals
  const completedTotalElement = document.getElementById('completedTotal')
  if (completedTotalElement) {
    completedTotalElement.textContent = completedCount
  }

  // Update invoicedDealsCount
  const invoicedDealsElement = document.getElementById('invoicedDeals')
  if (invoicedDealsElement) {
    invoicedDealsElement.textContent = invoicedDealsCount
  }

  // Update quotedDealsElement
  const quotedDealsElement = document.getElementById('totalQuotedDeals')
  if (quotedDealsElement) {
    quotedDealsElement.textContent = quotededDealsCount
  }
}

const linkCards = async (crmData, crmItems) => {
  // console.log('crmData On lnkCard function', crmData);
  // Add event listeners to log card details on click
  document.querySelectorAll('.dashboard-card').forEach(card => {
    card.addEventListener('click', () => {
      const cardTitle = card.querySelector('.card-title').textContent

      if (cardTitle === 'OverDue Deals') {
        console.log('OverDue Deals')
        const overdueDealsCount = crmItems
          .filter(item => {
            const followUpDate = new Date(item.followUp)
            return !isNaN(followUpDate) && followUpDate < new Date()
          })
          .reduce((acc, item) => {
            const existingItem = acc.find(i => i.trnNo === item.trnNo)
            if (
              !existingItem ||
              new Date(existingItem.followUp) < new Date(item.followUp)
            ) {
              acc = acc.filter(i => i.trnNo !== item.trnNo)
              acc.push(item)
            }
            return acc
          }, [])
        showDetailTableTab()
        populateCrmItems(overdueDealsCount, crmData)
      } else if (cardTitle === 'Lead Deals') {
        const initialDealsCount = crmItems.filter(
          item => item.stage === 'initial'
        )
        // console.log('Lead Deals', initialDealsCount);
        showDetailTableTab()
        populateCrmItems(initialDealsCount, crmData)
      } else if (cardTitle === 'In-Progress Deals') {
        const inProgressDealsCount = crmItems.filter(
          item => item.stage === 'in-progress'
        )
        showDetailTableTab()
        populateCrmItems(inProgressDealsCount, crmData)
        // console.log('In-Progress Deals', inProgressDealsCount)
      } else if (cardTitle === 'Quoted Deals') {
        const quotededDealsCount = crmItems.filter(
          item => item.type === 'Quote'
        )
        showDetailTableTab()
        populateCrmItems(quotededDealsCount, crmData)
      } else if (cardTitle === 'Invoiced Deals') {
        const invoicedDealsCount = crmItems.filter(
          item => item.type === 'Invoice'
        )
        showDetailTableTab()
        populateCrmItems(invoicedDealsCount, crmData)
      } else if (cardTitle === 'Completed Deals') {
        const completedCount = crmItems.filter(
          item => item.stage === 'completed'
        )
        showDetailTableTab()
        populateCrmItems(completedCount, crmData)
      } else if (cardTitle === 'Continuous Deals') {
        const continuosDealsCount = crmItems.filter(
          item => item.stage === 'continuous'
        )
        showDetailTableTab()
        populateCrmItems(continuosDealsCount, crmData)
      }
    })
  })
}

// Function to filter events based on the status
function filterDeals (filter) {
  const allCards = document.querySelectorAll('.Deals-container .event-card')

  allCards.forEach(card => {
    const cardStatus = card.getAttribute('data-status') // Get the card's status

    if (filter === 'all' || cardStatus === filter) {
      card.style.display = '' // Show the card
    } else {
      card.style.display = 'none' // Hide the card
    }
  })
}

// Attach event listeners to filter buttons
document.querySelectorAll('#eventFilters button').forEach(button => {
  button.addEventListener('click', event => {
    // Remove 'active' class from all buttons and add it to the clicked one
    document
      .querySelectorAll('#eventFilters button')
      .forEach(btn => btn.classList.remove('active'))
    button.classList.add('active')

    // Get the filter type from the button's data-filter attribute
    const filter = button.getAttribute('data-filter')
    filterDeals(filter) // Call the filter function with the selected filter
  })
})

//------------------------Main Table Start------------------------Main Table Start------------------------Main Table Start------------------------Main Table Start

function populateCrmData (crmData, crmItems, users) {
  //console.log('fetched crm data', crmData);
  crmData.sort((a, b) => new Date(b.date) - new Date(a.date))
  crmData.sort((a, b) => b.id - a.id)

  // Extract and log the time from created_at for each row
  crmData.forEach(row => {
    const createdAt = new Date(row.created_at)
    const time = createdAt.toTimeString().split(' ')[0] // Extract the time portion
    row.time = time // Add the extracted time to the row object
    // console.log(`ID: ${row.id}, Created At: ${row.created_at}, Time: ${time}`);
  })

  const mainTable = document.getElementById('mainTable')
  const mainTableModal = new bootstrap.Modal(
    document.getElementById('mainTableModal')
  )
  const newDetaiTableModal = new bootstrap.Modal(
    document.getElementById('detaiTableModal')
  )

  let filteredData = crmData
  // console.log('filteredData', filteredData)

  // Search Input Listener
  document
    .getElementById('searchDealsTable')
    .addEventListener('input', function (e) {
      const query = e.target.value.toLowerCase() // Get the search query and convert to lowercase

      // Filter the original data (crmData)
      filteredData = crmData.filter(row => {
        return Object.values(row).some(
          value => value && value.toString().toLowerCase().includes(query)
        )
      })
      // Update the Handsontable instance with filtered data
      hot.loadData(filteredData)
    })

  // Stock Grid Configuration
  const hot = new Handsontable(mainTable, {
    data: filteredData,
    columns: [
      {
        data: 'id',
        title: 'DEAL No',
        type: 'text'
      },
      {
        data: 'date',
        title: 'DATE',
        type: 'date',
        dateFormat: 'YYYY-MM-DD'
      },
      {
        data: 'description',
        title: 'DESCRIPTION',
        type: 'numeric'
      },
      {
        data: 'salesRep',
        title: 'REP',
        type: 'text'
      },
      {
        data: 'partner',
        title: 'PARTNER',
        type: 'text'
      },
      {
        data: 'customer',
        title: 'CUSTOMER',
        type: 'text'
      },
      {
        data: 'cusTel',
        title: 'CONTACT',
        type: 'text'
      },
      {
        data: 'partnerRep',
        title: 'PARTNER REP',
        type: 'text'
      },
      {
        data: 'stage',
        title: 'STAGE',
        type: 'text'
      },
      {
        data: 'multyTender',
        title: 'MT-No',
        type: 'text'
      },
      {
        data: 'time',
        title: 'TIME',
        type: 'text'
      }
    ],
    rowHeaders: false,
    colHeaders: true,
    height: 500,
    width: '100%',
    stretchH: 'all',
    manualColumnResize: true, // Enable column resizing
    sortIndicator: true,
    columnSorting: {
      sortEmptyCells: true, // Sort empty cells last
      compareFunctionFactory (sortOrder, columnMeta) {
        if (columnMeta.data === 'date') {
          return function (a, b) {
            // Parse the dates (e.g., "YYYY-MM-DD") into `Date` objects
            const dateA = a[1] ? new Date(a[1]) : new Date(0) // Handle null/empty dates
            const dateB = b[1] ? new Date(b[1]) : new Date(0)

            if (dateA < dateB) return sortOrder === 'asc' ? -1 : 1
            if (dateA > dateB) return sortOrder === 'asc' ? 1 : -1
            return 0 // If dates are equal, keep the same order
          }
        }
        return function (a, b) {
          return sortOrder === 'asc' ? a[1] - b[1] : b[1] - a[1]
        }
      }
    },
    filters: true, // Enable the filters plugin
    dropdownMenu: true, // Enable dropdown menu for filtering
    colWidths: [
      45, // Width for 'DEAL No'
      60, // Width for 'DATE'
      300, // Width for 'DESCRIPTION' (make this larger)
      50, // Width for 'SALES REP'
      125, // Width for 'PARTNER'
      125, // Width for 'CUSTOMER'
      65, // Width for 'Contact'
      65, // Width for 'PARTNER REP'
      60, // Width for 'STAGE'
      30, // Width for 'MULTiTENDER'
      35 // Width for 'TIME'
    ],
    cells: function (row, col) {
      const cellProperties = {}
      const rowData = this.instance.getSourceDataAtRow(row)

      // Ensure rowData is defined before accessing its properties
      if (rowData) {
        const followUpDates = rowData.followUp || []
        const stage = rowData.stage ? rowData.stage.trim().toLowerCase() : ''

        // Highlight only if the stage is not "completed"
        if (
          stage !== 'completed' &&
          Array.isArray(followUpDates) &&
          followUpDates.length > 0
        ) {
          // Convert follow-up dates to Date objects
          const validDates = followUpDates
            .map(date => new Date(date))
            .filter(date => !isNaN(date)) // Remove invalid dates

          if (validDates.length > 0) {
            // Calculate the latest follow-up date
            const latestFollowUpDate = new Date(Math.max(...validDates))
            const currentDate = new Date()

            // Apply conditional formatting
            if (
              latestFollowUpDate.toDateString() === currentDate.toDateString()
            ) {
              cellProperties.className = 'highlight-today' // Highlight for today's date
            } else if (latestFollowUpDate < currentDate) {
              cellProperties.className = 'highlight-past' // Highlight for past date
            } else if (latestFollowUpDate > currentDate) {
              cellProperties.className = 'highlight-future' // Highlight for future date
            }
          } else {
            console.warn(`No valid follow-up dates for row: ${row}`)
          }
        }
      } else {
        console.warn(`Row data is undefined or null for row: ${row}`)
      }

      return cellProperties
    },

    contextMenu: {
      items: {
        new_deal: {
          name: '<i class="fa fa-plus-circle"></i> Add New Deal',
          callback: function (key, selection) {
            if (userCategory != 'technical') {
              const selectedRowIndex = selection[0].start.row
              const selectedRowData = hot.getDataAtRow(selectedRowIndex)
              document.getElementById('dealSalesRep').value =
                selectedRowData[3] || ''

              fetch('../../functions/fetchCrmData.php')
                .then(response => {
                  if (!response.ok) {
                    throw new Error('Error while fetching CRM Data for ID')
                  }
                  return response.json()
                })
                .then(response => {
                  if (response.length > 0) {
                    const lastId = response[response.length - 1].id // Access the last item's `id`
                    document.getElementById('dealNo').value = Number(lastId) + 1
                  } else {
                    console.log('crmItems is empty.')
                  }
                })
                .catch(error => {
                  console.error('Error while fetching CRM Data for ID')
                })

              mainTableModal.show()
            } else {
              Swal.fire({
                toast: true,
                icon: 'error',
                title: 'You are not authorized to add a new deal',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
              })
            }
          }
        },
        edit_deal: {
          name: '<i class="fa fa-pencil-alt"></i> Edit This Deal',
          callback: function (key, selection) {
            console.log(userCategory)
            if (userCategory != 'technical') {
              const selectedRowIndex = selection[0].start.row
              const selectedRowData = hot.getDataAtRow(selectedRowIndex)

              // Get the value of the first column (trnNo)
              const selectedTrnNo = selectedRowData[0]
              document.getElementById('dealNo').value = selectedTrnNo || '' // Ensure a fallback value
              document.getElementById('dealDate').value =
                selectedRowData[1] || ''
              document.getElementById('dealDescription').value =
                selectedRowData[2] || ''
              document.getElementById('dealSalesRep').value =
                selectedRowData[3] || ''
              document.getElementById('cusTel').value = selectedRowData[6] || ''

              const trimmeddealCustomer = (selectedRowData[5] || '').trim()
              if (
                $('#dealCustomer option').filter(function () {
                  return $(this).val().trim() === trimmeddealCustomer
                }).length > 0
              ) {
                $('#dealCustomer').val(trimmeddealCustomer).trigger('change')
              } else {
                console.warn(
                  'Customer not found in dropdown:',
                  trimmeddealCustomer
                )
              }

              const trimmeddealPartner = (selectedRowData[4] || '').trim()
              if (
                $('#dealPartner option').filter(function () {
                  return $(this).val().trim() === trimmeddealPartner
                }).length > 0
              ) {
                $('#dealPartner').val(trimmeddealPartner).trigger('change')
              } else {
                console.warn(
                  'Partner not found in dropdown:',
                  trimmeddealPartner
                )
              }

              setTimeout(() => {
                const trimmeddealPartRep = (selectedRowData[7] || '').trim()
                $('#dealPartRep option').each(function () {
                  if ($(this).val().trim() === trimmeddealPartRep) {
                    $('#dealPartRep').val($(this).val()).trigger('change')
                  }
                })
              }, 1000)

              const trimmeddealStage = (selectedRowData[8] || '').trim()
              if (
                $('#dealStage option').filter(function () {
                  return $(this).val().trim() === trimmeddealStage
                }).length > 0
              ) {
                $('#dealStage').val(trimmeddealStage).trigger('change')
              } else {
                console.warn(
                  'Stage is not found in dropdown:',
                  trimmeddealStage
                )
              }

              const trimmeddealTender = (selectedRowData[9] || '').trim()
              if (
                $('#multyTender option').filter(function () {
                  return $(this).val().trim() === trimmeddealTender
                }).length > 0
              ) {
                $('#multyTender').val(trimmeddealTender).trigger('change')
              } else {
                console.warn(
                  'Stage is not found in dropdown:',
                  trimmeddealTender
                )
              }
              mainTableModal.show()
            } else {
              Swal.fire({
                toast: true,
                icon: 'error',
                title: 'You are not authorized to edit this deal',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
              })
            }
          }
        },
        view_Events: {
          name: '<i class="fa fa-eye"></i> View Deal Actions',
          callback: function (key, selection) {
            const selectedRowIndex = selection[0].start.row
            const selectedRowData = hot.getDataAtRow(selectedRowIndex)

            // Get the value of the first column (trnNo)
            const selectedTrnNo = selectedRowData[0]
            const selectedDescription = selectedRowData[2]

            // Filter crmItems based on trnNo
            const filteredItems = crmItems.filter(
              item => item.trnNo === selectedTrnNo
            )

            // Call the function to show the tab
            showDetailTableTab()
            populateCrmItems(filteredItems, crmData)
          }
        },
        add_deal_detail: {
          name: '<i class="fa fa-plus"></i> Add Deal Action',
          callback: function (key, selection) {
            const selectedRowIndex = selection[0].start.row
            const selectedRowData = hot.getDataAtRow(selectedRowIndex)

            document.getElementById('trnNo').value = selectedRowData[0]

            newDetaiTableModal.show()

            var modalElement = document.getElementById('detaiTableModal')

            // Reset the display property and show the modal
            modalElement.style.display = 'block' // Ensure it's set to block before showing
            var modalInstance = new bootstrap.Modal(modalElement)
            modalInstance.show() // Use Bootstrap's modal.show() method
          }
        },
        remove_stock: {
          name: '<i class="fa fa-trash"></i> Remove This Deal',
          callback: function (key, selection) {
            if (userCategory != 'technical') {
              const selectedRow = selection[0].start.row // Get the selected row index
              const selectedRowData = hot.getDataAtRow(selectedRow) // Get data of the selected row

              // Extract the id from the selected row data
              const dealId = selectedRowData[0] // Assuming the 'id' is in the first column

              // Confirm the deletion action
              if (confirm(`Are you sure you want to remove this Deal`)) {
                removeData(dealId, 'crmdata')

                crmData.splice(selectedRow, 1)
                hot.render()
              }
            } else {
              Swal.fire({
                toast: true,
                icon: 'error',
                title: 'You are not authorized to remove this deal',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
              })
            }
          }
        },
        view_customer_info: {
          name: '<i class="fa fa-user"></i> View Customer Info',
          callback: function (key, selection) {
            const row = selection[0].start.row
            const rowData = this.getDataAtRow(row)
            populateCustomerModal(rowData[5], 'end_customer')
          }
        },
        view_partner_info: {
          name: '<i class="fa fa-building"></i> View Partner Info',
          callback: function (key, selection) {
            const row = selection[0].start.row
            const rowData = this.getDataAtRow(row)
            populateCustomerModal(rowData[4], 'partner')
          }
        },
        refresh: {
          name: 'Refresh Table',
          callback: function (key, options) {
            fetchData()
            setTimeout(() => {
              refreshCrmData()
            }, 1000) // Ensures enough time for fetch to complete
            hot.render()
          }
        }
      }
    },
    licenseKey: 'non-commercial-and-evaluation'
  })
}

async function populateCustomerModal (customer, type) {
  const customerModal = new bootstrap.Modal(
    document.getElementById('customerDetailsModal')
  )
  const employeesTableBody = document.getElementById('employeesTableBody')

  try {
    // Existing fetch request
    const customerResponse = await fetch('../../functions/fetchCustomers.php')
    if (!customerResponse.ok) throw new Error('Error while fetching customers')
    const customers = await customerResponse.json()

    // Filter customers by type "end_customer"
    const filteredCustomer = customers.filter(
      item => item.type === type && item.company_name == customer
    )[0]

    if (!filteredCustomer) throw new Error('Customer not found')

    // Populate company information
    document.getElementById('companyName').textContent =
      filteredCustomer.company_name
    document.getElementById('companyAddress').textContent =
      filteredCustomer.address
    document.getElementById('creditLimit').textContent =
      filteredCustomer.credit_limit
    document.getElementById('paymentTerms').textContent =
      filteredCustomer.payment_terms

    // New request to send the customer to the PHP script
    const response = await fetch(
      `../../functions/fetchCusEmployeeDetails.php?company=${encodeURIComponent(
        customer
      )}`
    )
    if (!response.ok) throw new Error('Error while sending customer to backend')
    const employeeData = await response.json()

    // Clear previous employees
    employeesTableBody.innerHTML = ''

    // Check if employeeData and cusEmpDetails are valid
    if (
      employeeData &&
      Array.isArray(employeeData.cusEmpDetails) &&
      employeeData.cusEmpDetails.length > 0
    ) {
      // Populate employees
      employeeData.cusEmpDetails.forEach(employee => {
        const row = document.createElement('tr')
        row.innerHTML = `
                    <td>${employee.cus_em_name}</td>
                    <td>${employee.email}</td>
                    <td>${employee.tel1}</td>
                    <td>${employee.tel2}</td>
                    <td>${employee.type}</td>
                `
        employeesTableBody.appendChild(row)
      })
    } else {
      // Display a message if no employees are found
      const row = document.createElement('tr')
      row.innerHTML = `<td colspan="5" style="text-align: center;">No employees found..! <button id="addEmployee" class="btn text-info bg-transparent" type="button">Add Employee</button></td>`
      employeesTableBody.appendChild(row)
      // Add event listener to the "Add Employee" button
      // Add event listener to the "Add Employee" button
      const addEmployeeButton = document.getElementById('addEmployee')
      addEmployeeButton.addEventListener('click', () => {
        // Hide the parent modal
        const customerModalElement = document.getElementById(
          'customerDetailsModal'
        )
        const customerModalInstance =
          bootstrap.Modal.getInstance(customerModalElement)
        customerModalInstance.hide()

        // Show the child modal
        const employeeModalElement = document.getElementById(
          'customerEmployeeModal'
        )
        const employeeModalInstance = new bootstrap.Modal(employeeModalElement)
        document.getElementById('EmplyeeModalcustomer').value =
          filteredCustomer.company_name
        employeeModalInstance.show()

        // Listen for when the child modal is hidden
        employeeModalElement.addEventListener(
          'hidden.bs.modal',
          () => {
            // Reopen the parent modal
            customerModalInstance.show()
          },
          { once: true }
        )
      })
    }

    // Show the modal
    customerModal.show()

    //populatePartnerRep(value);
  } catch (error) {
    console.error('An error occurred:', error.message)
    alert('Failed to load customer details')
  }
}

//------------------------Main Table Ended------------------------Main Table Ended------------------------Main Table Ended------------------------Main Table Ended

//---------------------DETAIL TABLE STAR---------------------DETAIL TABLE STAR---------------------DETAIL TABLE STAR---------------------DETAIL TABLE STAR

const filterButtons = document.querySelectorAll('#eventFilters button')
const eventCards = document.querySelectorAll('.event-card')

filterButtons.forEach(button => {
  button.addEventListener('click', function () {
    // Remove active class from all buttons
    filterButtons.forEach(btn => btn.classList.remove('active'))
    // Add active class to clicked button
    this.classList.add('active')

    const filterValue = this.dataset.filter

    // Show/hide cards based on filter
    eventCards.forEach(card => {
      if (filterValue === 'all') {
        card.classList.remove('hidden')
      } else {
        if (card.dataset.status === filterValue) {
          card.classList.remove('hidden')
        } else {
          card.classList.add('hidden')
        }
      }
    })
  })
})

function populateCrmItems (crmItems, crmData) {
  // console.log('newData fetched',crmItems)
  const popupEl = document.getElementById('hover-popup')

  // Sort CRM items by ID in descending order
  crmItems.sort((a, b) => b.id - a.id)

  const container = document.getElementById('detailTable')
  const detaiTableModal = new bootstrap.Modal(
    document.getElementById('detaiTableModal')
  )

  // Build an index for search
  const index = {}
  const searchableFields = [
    'trnNo',
    'date',
    'salesRep',
    'followUp',
    'fupUser',
    'brand',
    'model'
  ]

  crmItems.forEach((item, rowIndex) => {
    searchableFields.forEach(field => {
      if (item[field]) {
        const fieldValue = item[field].toString().toLowerCase()
        fieldValue.split(/\s+/).forEach(token => {
          if (!index[token]) {
            index[token] = []
          }
          index[token].push(rowIndex)
        })
      }
    })
  })

  function showRowPopup (event, rowData) {
    // console.log('crmData', crmData, 'rowData,', rowData);

    // Filter crmData to find matching entries
    const matchingData = crmData.filter(crmItem => crmItem.id === rowData.trnNo)

    // Check if matching data exists
    if (matchingData.length > 0) {
      const crmItem = matchingData[0] // Use the first matching entry

      // Create popup content using the matched CRM item properties
      const popupContent = `
                <h2><u>Deal Details</u></h2>
                <div><strong>Deal No:</strong> ${crmItem.id || 'N/A'}</div>
                <div><strong>Customer:</strong> ${
                  crmItem.customer || 'N/A'
                }</div>
                <div><strong>Customer Tel:</strong> ${
                  crmItem.cusTel || 'N/A'
                }</div>
                <div><strong>Date:</strong> ${crmItem.date || 'N/A'}</div>
                <div><strong>Sales Rep:</strong> ${
                  crmItem.salesRep || 'N/A'
                }</div>
                <div><strong>Stage:</strong> ${crmItem.stage || 'N/A'}</div>
                <div><strong>Description:</strong> ${
                  crmItem.description || 'N/A'
                }</div>
                <div><strong>Partner:</strong> ${crmItem.partner || 'N/A'}</div>
                <div><strong>Partner Rep:</strong> ${
                  crmItem.partnerRep || 'N/A'
                }</div>
            `

      // Position the popup near the mouse
      popupEl.innerHTML = popupContent
      popupEl.style.display = 'block'
      popupEl.style.left = `${event.pageX + 10}px`
      popupEl.style.top = `${event.pageY + 1}px`
    } else {
      // No matching data found
      const noDataContent = `<strong>No matching CRM data found for Deal No: ${rowData.trnNo}</strong>`
      popupEl.innerHTML = noDataContent
      popupEl.style.display = 'block'
      popupEl.style.left = `${event.pageX + 10}px`
      popupEl.style.top = `${event.pageY + 1}px`
    }
  }

  function hideRowPopup () {
    popupEl.style.display = 'none'
  }

  function computeHighlightData (crmItems) {
    const highlightData = {}
    const currentDate = new Date()

    crmItems.forEach((item, index) => {
      const followUpDate = new Date(item.followUp)
      if (!isNaN(followUpDate)) {
        highlightData[index] = {
          isLatest:
            item.trnNo &&
            crmItems
              .filter(data => data.trnNo === item.trnNo)
              .every(other => new Date(other.followUp) <= followUpDate),
          isToday: followUpDate.toDateString() === currentDate.toDateString(),
          isPast: followUpDate < currentDate,
          stage: item.stage?.toLowerCase()
        }
      }
    })

    return highlightData
  }

  let highlightData = computeHighlightData(crmItems)

  const hot = new Handsontable(container, {
    data: crmItems,
    columns: [
      { data: 'id', title: 'A NO', type: 'text', width: 0, visible: false },
      { data: 'trnNo', title: 'DEAL No', type: 'text' },
      { data: 'date', title: 'DATE', type: 'date', dateFormat: 'YYYY-MM-DD' },
      { data: 'action', title: 'ACTION', type: 'text', width: 150 },
      { data: 'salesRep', title: 'SALES REP', type: 'text' },
      { data: 'type', title: 'TYPE', type: 'text' },
      { data: 'media', title: 'MEDIA', type: 'text' },
      { data: 'followUp', title: 'FOLLOW UP', type: 'text' },
      { data: 'fupUser', title: 'FUP USER', type: 'text' },
      { data: 'fupAction', title: 'FUP ACTION', type: 'text', width: 150 },
      { data: 'brand', title: 'BRAND', type: 'text' },
      { data: 'model', title: 'MODEL', type: 'text' },
      { data: 'inv', title: 'INV', type: 'text' },
      { data: 'qtNo', title: 'QUOTE', type: 'text' },
      { data: 'supTicket', title: 'ST-No', type: 'text' },
      { data: 'gp', title: 'GP', type: 'text' },
      {
        data: 'gpMonth',
        title: 'GP-MONTH',
        type: 'date',
        dateFormat: 'YYYY-MM'
      }
    ],
    rowHeaders: false,
    colHeaders: true,
    height: 500,
    width: '100%',
    stretchH: 'all',
    filters: true,
    dropdownMenu: true,
    sortIndicator: true,
    columnSorting: true,
    licenseKey: 'non-commercial-and-evaluation',
    cells: function (row, col, prop) {
      const cellProperties = {}
      const data = highlightData[row]

      if (data?.isLatest) {
        if (data.isToday && data.stage !== 'complete') {
          cellProperties.className = 'highlight-today'
        } else if (data.isPast && data.stage !== 'complete') {
          cellProperties.className = 'highlight-past'
        }
      }

      cellProperties.renderer = function (
        instance,
        td,
        row,
        col,
        prop,
        value,
        cellProperties
      ) {
        // Default cell rendering
        Handsontable.renderers.TextRenderer.apply(this, arguments)

        // Add event listeners to the cell
        td.addEventListener('mouseover', e => {
          // Use getSourceDataAtRow instead of getDataAtRow
          const rowData = instance.getSourceDataAtRow(row)
          showRowPopup(e, rowData)
        })

        td.addEventListener('mouseout', hideRowPopup)
      }
      // console.log(cellProperties)
      return cellProperties
    },
    contextMenu: {
      items: {
        view_details: {
          name: '<i class="fa fa-pencil-alt"></i> Edit Action',
          callback: async function (key, selection) {
            const selectedRowIndex = selection[0].start.row
            const selectedRowData = hot.getDataAtRow(selectedRowIndex)

            // Fill out the form with selected row data
            document.getElementById('id').value = selectedRowData[0]
            document.getElementById('trnNo').value = selectedRowData[1]
            document.getElementById('date').value = selectedRowData[2]
            document.getElementById('action').value = selectedRowData[3]
            document.getElementById('salesRep').value = selectedRowData[4]
            document.getElementById('type').value = selectedRowData[5]
            document.getElementById('media').value = selectedRowData[6]
            document.getElementById('followup').value = selectedRowData[7]
            document.getElementById('fupAction').value = selectedRowData[9]
            document.getElementById('supTicket').value = selectedRowData[14]
            document.getElementById('gp').value = selectedRowData[15]
            document.getElementById('gpMonth').value = selectedRowData[16]

            if (
              selectedRowData[5] == 'Quote' ||
              selectedRowData[5] == 'Invoice'
            ) {
              document.getElementById('gpArea').hidden = false
            } else {
              document.getElementById('gpArea').hidden = true
            }

            // Populate Select2 dropdowns
            const userValue = selectedRowData[8]
            const brandValue = selectedRowData[10]
            const modelValue = selectedRowData[11]
            const invValue = selectedRowData[12]
            const quoteValue = selectedRowData[13]

            // Handle Select2 dropdown selection
            const trimmedUserValue = userValue ? userValue.trim() : 'N/A'
            if (
              $('#fup option').filter(function () {
                return $(this).val().trim() === trimmedUserValue
              }).length > 0
            ) {
              $('#fup').val(trimmedUserValue).trigger('change')
            }

            const trimmedBrandValue = brandValue ? brandValue.trim() : 'N/A'
            if (
              $('#brand option').filter(function () {
                return $(this).val().trim() === trimmedBrandValue
              }).length > 0
            ) {
              $('#brand').val(trimmedBrandValue).trigger('change')
            }

            const trimmedModelValue = modelValue ? modelValue.trim() : 'N/A'
            if (
              $('#model option').filter(function () {
                return $(this).val().trim() === trimmedModelValue
              }).length > 0
            ) {
              $('#model').val(trimmedModelValue).trigger('change')
            }

            document.getElementById('inv').value = invValue
            document.getElementById('quote').value = quoteValue
            const previewFrame = document.querySelector(
              '#filePreviewModal iframe'
            )

            viewFileBtn.addEventListener('click', async () => {
              try {
                const id = selectedRowData[0] // Use the ID from the selected row
                if (!id) {
                  alert('No file selected for viewing.')
                  return
                }

                const response = await fetch(
                  `../../functions/fetchCrmFile.php?id=${id}`
                )

                if (response.ok) {
                  // Directly set the response URL as the iframe source
                  previewFrame.src = `../../functions/fetchCrmFile.php?id=${id}`
                  const childModal = new bootstrap.Modal(
                    document.getElementById('filePreviewModal')
                  )
                  childModal.show()
                } else {
                  alert('Unable to fetch the file. Please try again.')
                  console.error('Fetch response:', await response.text())
                }
              } catch (error) {
                console.error('Error fetching file:', error)
                alert('An error occurred while fetching the file.')
              }
            })

            detaiTableModal.show()

            var modalElement = document.getElementById('detaiTableModal')

            // Reset the display property and show the modal
            modalElement.style.display = 'block' // Ensure it's set to block before showing
            var modalInstance = new bootstrap.Modal(modalElement)
            modalInstance.show() // Use Bootstrap's modal.show() method

            //console.log("Modal shown.");
          }
        },

        remove_stock: {
          name: '<i class="fa fa-trash"></i> Remove This Action',
          callback: function (key, selection) {
            const selectedRow = selection[0].start.row
            // removeData(id, tableName);
            const selectedRowData = hot.getDataAtRow(selectedRow) // Get data of the selected row

            // Extract the id from the selected row data
            const dealId = selectedRowData[0] // Assuming the 'id' is in the first column

            // Confirm the deletion action
            if (confirm(`Are you sure you want to remove this Deal`)) {
              removeData(dealId, 'crmitems')

              crmItems.splice(selectedRow, 1)
              hot.render()

              // Call your removeData function and pass the id
            }
          }
        },
        duplicate_row: {
          name: '    <i class="fa fa-mars-double" aria-hidden="true"></i>Duplicate Row',
          callback: function (key, selection) {
            const selectedRowIndex = selection[0].start.row // Get the index of the selected row
            const selectedRowData = hot.getSourceDataAtRow(selectedRowIndex) // Get the full data of the selected row

            if (selectedRowData) {
              // Clone the selected row data
              const newRowData = { ...selectedRowData }

              // Insert the cloned row into the crmItems array
              crmItems.splice(selectedRowIndex + 1, 0, newRowData)

              // Reload the Handsontable data to reflect changes
              hot.loadData(crmItems)
            } else {
              console.warn('No row data found to duplicate.')
            }
          }
        },
        refresh: {
          name: 'Refresh Table',
          callback: function (key, options) {
            fetchData()
            setTimeout(() => {
              refreshCrmData()
              refreshCrmItems()
            }, 1000) // Ensures enough time for fetch to complete
            hot.render()
          }
        }
      }
    }
  })

  // Search Input Listener
  document
    .getElementById('searchDealEvents')
    .addEventListener('input', function (e) {
      const query = e.target.value.toLowerCase() // Get the search query in lowercase
      const searchableFields = [
        'id',
        'trnNo',
        'date',
        'salesRep',
        'followUp',
        'fupUser',
        'brand',
        'model'
      ]

      // Filter data dynamically based on the query
      const filteredData = crmItems.filter(item => {
        return searchableFields.some(field => {
          return (
            item[field] && item[field].toString().toLowerCase().includes(query)
          )
        })
      })

      // Update Handsontable with the filtered data
      hot.loadData(filteredData)
    })
}

//---------------------Partner Loockup Tbale----------------------Send data to data base ---------------------Send data to data base ---------------------

function populatePartnerLoockup (crmData) {
  // console.log('crmData', crmData);

  // Select the container for Handsontable
  const container = document.getElementById('partnerLoockupTable')

  // Initialize Handsontable
  /*const hot = new Handsontable(container, {
        data: crmData, // Pass the fetched data
        colHeaders: [
            
        ],
        columns: [
            { data: 'salesRep', width: 80 }, // Set fixed width for 'Sales Rep' column
            { data: 'customer', width: 200 }, // Set fixed width for 'Customer' column
            { data: 'description', width: 300 }, // Set fixed width for 'Description' column
            { data: 'partner', width: 250 }, // Set fixed width for 'Partner' column
            { data: 'partnerRep', width: 150 }, // Set fixed width for 'Partner Rep' column
            { data: 'stage', width: 80 }, // Set fixed width for 'Stage' column
            { data: 'date', width: 100 } // Set fixed width for 'Date' column
        ],
        rowHeaders: true,
        height: 500,
        width: '100%',
        stretchH: 'all',
        manualColumnResize: true,
        licenseKey: 'non-commercial-and-evaluation' // Replace with your license key if applicable
    });

    // Add event listener to the search input
    const searchInput = document.getElementById('partnerLoockup');
    searchInput.addEventListener('input', function () {
        const searchValue = searchInput.value.toLowerCase();

        // Filter the data based on the input value
        const filteredData = crmData.filter(row =>
            Object.values(row).some(value =>
                value.toString().toLowerCase().includes(searchValue)
            )
        );

        // Update Handsontable with the filtered data
        hot.loadData(filteredData);
    });*/
}

//---------------------Send data to data base ---------------------Send data to data base ---------------------Send data to data base ---------------------Send data to data base

document.getElementById('dealBtn').addEventListener('click', function () {
  // Collect form data

  const dealNo = document.getElementById('dealNo').value
  const dealDate = document.getElementById('dealDate').value
  const dealSalesRep = document.getElementById('dealSalesRep').value
  const dealDescription = document.getElementById('dealDescription').value
  const dealCustomer = document.getElementById('dealCustomer').value
  const cusTel = document.getElementById('cusTel').value
  const dealPartner = document.getElementById('dealPartner').value
  const dealPartRep = document.getElementById('dealPartRep').value
  const dealStage = document.getElementById('dealStage').value
  const multyTender = document.getElementById('multyTender').value

  if (!dealDate || !dealDescription || !dealStage) {
    // Highlight missing fields with red border
    if (!dealDate) document.getElementById('dealDate').style.borderColor = 'red'
    if (!dealDescription)
      document.getElementById('dealDescription').style.borderColor = 'red'
    if (!dealStage)
      document
        .getElementById('dealStage')
        .parentElement.querySelector('.select2-selection').style.borderColor =
        'red'
    Swal.fire({
      toast: true,
      icon: 'error',
      title: 'please fill the required fields!',
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    })
    return
  }

  const dealData = {
    dealNo: dealNo,
    dealDate: dealDate,
    dealSalesRep: dealSalesRep,
    dealDescription: dealDescription,
    dealCustomer: dealCustomer,
    cusTel: cusTel,
    dealPartner: dealPartner,
    dealPartRep: dealPartRep,
    dealStage: dealStage,
    multyTender: multyTender
  }

  // Prepare the request options
  const requestOptions = {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(dealData) // Convert the data to JSON
  }

  // Send the data to the backend
  fetch('../../functions/updateCrmData.php', requestOptions)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok')
      }
      return response.json() // Assuming the backend responds with JSON
    })
    .then(data => {
      // console.log('Success:', data)
      // Optionally, you can close the modal or reset the form here
      Swal.fire({
        toast: true,
        icon: 'success',
        title: data.message || 'Data saved successfully!',
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      }).then(() => {
        fetchData()
        setTimeout(() => {
          refreshCrmData()
        }, 1000) // Ensures enough time for fetch to complete
        hot.render()
      })
      // Close the modal
      const modalElement = document.getElementById('mainTableModal')
      const modalInstance = bootstrap.Modal.getInstance(modalElement) // Bootstrap 5 method
      if (modalInstance) {
        modalInstance.hide() // Close the modal
        refreshCrmData()
      }
    })
    .catch(error => {
      console.error('Error:', error)
    })
})

document
  .getElementById('eventBtn')
  .addEventListener('click', async function () {
    // Collect data from the form
    const id = document.getElementById('id').value || null
    const trnNo = document.getElementById('trnNo').value
    const date = document.getElementById('date').value
    const salesRep = document.getElementById('salesRep').value
    const action = document.getElementById('action').value
    const type = document.getElementById('type').value
    const followup = document.getElementById('followup').value
    const fup = document.getElementById('fup').value
    const fupAction = document.getElementById('fupAction').value
    const brand = document.getElementById('brand').value
    const model = document.getElementById('model').value
    const inv = document.getElementById('inv').value
    const quote = document.getElementById('quote').value
    const media = document.getElementById('media').value
    const supTicket = document.getElementById('supTicket').value
    const gp = document.getElementById('gp').value || null
    const gpMonth = document.getElementById('gpMonth').value || null

    if (!date || !action || !followup || !fupAction) {
      // Highlight missing fields with red border
      if (!date) {
        document.getElementById('date').style.borderColor = 'red'
      } else {
        document.getElementById('date').style.borderColor = ''
      }
      if (!action) {
        document.getElementById('action').style.borderColor = 'red'
      } else {
        document.getElementById('action').style.borderColor = ''
      }
      if (!type) {
        document
          .getElementById('type')
          .parentElement.querySelector('.select2-selection').style.borderColor =
          'red'
      } else {
        document
          .getElementById('type')
          .parentElement.querySelector('.select2-selection').style.borderColor =
          ''
      }
      if (!followup) {
        document.getElementById('followup').style.borderColor = 'red'
      } else {
        document.getElementById('followup').style.borderColor = ''
      }
      if (!fup) {
        $('.select2-first')
          .next('.select2-container')
          .addClass('select2-red-border')
      } else {
        $('.select2-first')
          .next('.select2-container')
          .removeClass('select2-red-border')
      }
      if (!fupAction) {
        document.getElementById('fupAction').style.borderColor = 'red'
      } else {
        document.getElementById('fupAction').style.borderColor = ''
      }
      if (!media) {
        document.getElementById('media').style.borderColor = 'red'
      } else {
        document.getElementById('media').style.borderColor = ''
      }
      Swal.fire({
        toast: true,
        icon: 'error',
        title: 'please fill the required fields!',
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
      })
      return
    } else {
      document.getElementById('date').style.borderColor = ''
      document.getElementById('action').style.borderColor = ''
      document
        .getElementById('type')
        .parentElement.querySelector('.select2-selection').style.borderColor =
        ''
      document.getElementById('followup').style.borderColor = ''
      $('.select2-first')
        .next('.select2-container')
        .removeClass('select2-red-border')
      document.getElementById('fupAction').style.borderColor = ''
      document.getElementById('media').style.borderColor = ''
    }

    const dealData = {
      id: id,
      trnNo: trnNo,
      date: date,
      salesRep: salesRep,
      action: action,
      type: type,
      followup: followup,
      fup: fup,
      fupAction: fupAction,
      brand: brand,
      model: model,
      inv: inv,
      quote: quote,
      media: media,
      supTicket: supTicket,
      gp: gp,
      gpMonth: gpMonth
    }

    //console.log('DealData:', dealData);

    // Handle optional file upload
    const fileInput = document.getElementById('fileUpload')
    const file = fileInput.files[0]

    if (file) {
      // If a file is provided, upload it
      const formData = new FormData()
      formData.append('file', file)
      formData.append('id', dealData.id) // Append the ID to the form data

      try {
        const fileUploadResponse = await fetch(
          '../../functions/saveCrmUpload.php',
          {
            method: 'POST',
            body: formData
          }
        )

        const fileUploadResult = await fileUploadResponse.json()
        //console.log('File Upload Response:', fileUploadResult);

        if (!fileUploadResult.success) {
          //console.log(`File upload failed: ${fileUploadResult.message || 'Unknown error'}`);
          Swal.fire({
            title: 'Error!',
            text:
              fileUploadResult.message ||
              'An error occurred while uploading the file.',
            icon: 'error',
            confirmButtonText: 'OK'
          })
          return // Stop further processing if file upload fails
        }
      } catch (error) {
        console.error('Error uploading file:', error)
        Swal.fire({
          title: 'Error!',
          text: 'An error occurred while uploading the file.',
          icon: 'error',
          confirmButtonText: 'OK'
        })
        return // Stop further processing if file upload fails
      }
    }

    fetch('../../functions/updateCrmEvent.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(dealData)
    })
      .then(response => {
        if (!response.ok) {
          console.error(
            `Network response was not ok. Status: ${response.status}, StatusText: ${response.statusText}`
          )
          return response.text().then(errorDetails => {
            console.error('Backend response details:', errorDetails)
            throw new Error('Failed to fetch from the backend.')
          })
        }
        return response.text()
      })
      .then(data => {
        // console.log('Success:', data);

        // Success toast message
        Swal.fire({
          toast: true,
          icon: 'success',
          title: data.message || 'Data saved successfully!',
          position: 'top-end',
          showConfirmButton: false,
          timer: 1000,
          timerProgressBar: true
        }).then(() => {
          fetchData()
          setTimeout(() => {
            refreshCrmItems()
          }, 3000) // Ensures enough time for fetch to complete
          hot.render()
        })

        var modalElement = document.getElementById('detaiTableModal')

        // Clear all input fields inside the modal
        var inputs = modalElement.querySelectorAll('input, textarea, select')
        inputs.forEach(input => {
          if (input.type === 'checkbox' || input.type === 'radio') {
            input.checked = false // Clear checkboxes and radio buttons
          } else {
            input.value = '' // Clear text inputs, textareas, and select options
          }
        })

        // Forcefully remove the 'show' class and hide the modal
        modalElement.classList.remove('show')
        modalElement.style.display = 'none' // Set display to none
        document.body.classList.remove('modal-open') // Remove modal-open class

        // Remove modal backdrop if it exists
        document
          .querySelectorAll('.modal-backdrop')
          .forEach(backdrop => backdrop.remove())

        // Dispatch the hidden event
        var event = new Event('hidden.bs.modal')
        modalElement.dispatchEvent(event)

        //console.log("Modal hidden, fields cleared, and cleaned up.");
      })
      .catch(error => {
        console.error('Error:', error)

        // Error toast message
        Swal.fire({
          toast: true,
          icon: 'error',
          title: 'An error occurred while saving the data.',
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        })
      })
  })

// File preview functionality
const fileInput = document.getElementById('fileUpload')
const viewFileBtn = document.getElementById('viewFilesBtn')
const previewFrame = document.querySelector('#filePreviewModal iframe')

fileInput.addEventListener('change', () => {
  const file = fileInput.files[0]
  if (file) {
    const fileURL = URL.createObjectURL(file)
    previewFrame.src = fileURL
  }
})

viewFileBtn.addEventListener('click', () => {
  if (fileInput.files.length > 0) {
    const childModal = new bootstrap.Modal(
      document.getElementById('filePreviewModal')
    )
    childModal.show()
  } else {
    // alert('Please select a file first.');
  }
})

// Function to collect and log data
async function collectCustomerData () {
  let companyName = document.getElementById('company-name').value.trim()
  let address = document.getElementById('address').value.trim()
  let paymentTerms = document.getElementById('payment-terms').value.trim()
  let creditLimit = document.getElementById('credit-limit').value.trim()
  let companyType = document.getElementById('companyType').value.trim()
  let vatNumber = document.getElementById('vat-number').value.trim()
  let customerNotes = document.getElementById('customer-notes').value.trim()

  if (!companyName || !address || companyType == 'select' || !companyType) {
    // Highlight missing fields with red border
    if (!companyName) {
      document.getElementById('company-name').style.borderColor = 'red'
    } else {
      document.getElementById('company-name').style.borderColor = ''
    }
    if (!address) {
      document.getElementById('address').style.borderColor = 'red'
    } else {
      document.getElementById('address').style.borderColor = ''
    }
    if (!companyType || companyType == 'select') {
      document.getElementById('companyType').style.borderColor = 'red'
    } else {
      document.getElementById('companyType').style.borderColor = ''
    }
    Swal.fire({
      toast: true,
      icon: 'error',
      title: 'please fill the required fields!',
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    })
    return
  } else {
    document.getElementById('company-name').style.borderColor = ''
    document.getElementById('address').style.borderColor = ''
    document.getElementById('companyType').style.borderColor = ''
  }

  const customerData = {
    companyName: companyName,
    address: address,
    paymentTerms: paymentTerms,
    creditLimit: creditLimit,
    companyType: companyType,
    vatNumber: vatNumber,
    customerNotes: customerNotes
  }

  // console.log('Customer Data:', customerData.companyType);

  try {
    const response = await fetch('../../functions/insertCustomer.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(customerData)
    })

    if (!response.ok) throw new Error('Network response was not ok')
    const data = await response.text()

    if (data.includes('Customer added successfully')) {
      // Reset the modal form
      document.querySelector('#customerModal form').reset()

      // Update relevant dropdowns or data
      if (customerData.companyType === 'end_customer') {
        await populateCustomers()
      } else if (customerData.companyType === 'partner') {
        await populatePartners()
      } else {
        Swal.fire({
          title: 'Warning!',
          text: 'Please select at least one customer type.',
          icon: 'warning',
          confirmButtonText: 'OK'
        })
        return
      }

      // Success message
      Swal.fire({
        title: 'Success!',
        text: 'Customer added successfully!',
        icon: 'success',
        confirmButtonText: 'OK'
      }).then(() => {
        // Close the modal after success
        $('#customerModal').modal('hide')
      })
    } else {
      Swal.fire({
        title: 'Error!',
        text: data || 'An error occurred while adding the customer.',
        icon: 'error',
        confirmButtonText: 'OK'
      })
      console.error(`Server Error: ${data}`)
    }
  } catch (error) {
    Swal.fire({
      title: 'Error!',
      text: 'An unexpected error occurred. Please try again.',
      icon: 'error',
      confirmButtonText: 'OK'
    })
    console.error('Error:', error)
  }
}

// Attach event listener to the Create button
document
  .getElementById('createCustomer')
  .addEventListener('click', collectCustomerData)

// Send data via AJAX
document.getElementById('saveEmployee').addEventListener('click', function () {
  // Collect values from the form fields
  const ceName = document.getElementById('ce-name').value.trim()
  const email = document.getElementById('email').value.trim()
  const tel01 = document.getElementById('tel01').value.trim()
  const tel02 = document.getElementById('tel02').value.trim()
  const dob = document.getElementById('dob').value.trim()
  const companyType = document.getElementById('company-type').value
  const partner = document.getElementById('EmplyeeModalcustomer').value.trim()
  const notes = document.getElementById('employeeNotes').value.trim()

  // Perform basic validation
  if (!ceName || !tel01 || !companyType || companyType == 'select') {
    if (!ceName) {
      document.getElementById('ce-name').style.borderColor = 'red'
    } else {
      document.getElementById('ce-name').style.borderColor = ''
    }
    if (!tel01) {
      document.getElementById('tel01').style.borderColor = 'red'
    } else {
      document.getElementById('tel01').style.borderColor = ''
    }
    if (!companyType || companyType == 'select') {
      document.getElementById('company-type').style.borderColor = 'red'
    } else {
      document.getElementById('company-type').style.borderColor = ''
    }

    Swal.fire({
      title: 'Error',
      text: 'Please fill in all required fields',
      icon: 'error',
      confirmButtonText: 'OK'
    })
    return
  }

  // Prepare the data object
  const employeeData = {
    ce_name: ceName,
    dob: dob,
    address: '', // Add if address is available in your modal
    email: email,
    tel1: tel01,
    tel2: tel02,
    type: companyType,
    notes: notes,
    company: partner
  }

  // Send data via AJAX
  $.ajax({
    url: '../../functions/insertCustomerEmployee.php', // Replace with your PHP script path
    type: 'POST',
    data: employeeData,
    success: function (response) {
      //console.log('Server Response:', response);

      if (response.success) {
        Swal.fire({
          title: 'Success!',
          text: 'Employee added successfully!',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          $('#customerEmployeeModal').modal('hide') // Close the modal
          populatePartnerRep()
        })
      } else {
        Swal.fire({
          title: 'Error',
          text: response.message || 'Unable to save employee.',
          icon: 'error',
          confirmButtonText: 'OK'
        })
        //console.log('Response Message:', response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error:', status, error)
      Swal.fire({
        title: 'Error',
        text: 'An unexpected error occurred. Please try again.',
        icon: 'error',
        confirmButtonText: 'OK'
      })
    }
  })
})

//------------------------Detrail Table Ended------------------------Detrail Table Ended------------------------Detrail Table Ended------------------------Detrail Table Ended

function removeData (id, tableName) {
  console.log('ID to delete:', id)
  console.log('Table name:', tableName)

  if (!id) {
    console.error('ID is undefined or null. Cannot proceed with deletion.')
    return
  }

  // Prepare data to send to the server in URL-encoded format
  const data = new URLSearchParams()
  data.append('inv', id)
  data.append('table', tableName)
  data.append('primaryKey', 'id')

  // Send the request as application/x-www-form-urlencoded
  fetch('../../functions/publicDelete.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: data.toString() // Serialize data
  })
    .then(response => response.text())
    .then(text => {
      if (text.startsWith('<')) {
        console.error('Server returned an HTML response:', text)
        alert('An unexpected server error occurred. Please contact support.')
        return
      }

      try {
        const result = JSON.parse(text)
        if (result.success) {
          Swal.fire({
            title: 'Success!',
            text: data.message || 'Data Deleted successfully!',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(() => {
            location.reload()
          })
        } else if (result.error) {
          console.error(result.error)
          alert(result.error)
        }
      } catch (error) {
        console.error('Response parsing error:', error)
        alert('An unexpected error occurred. Please try again.')
      }
    })
    .catch(error => {
      console.error('Fetch error:', error)
    })
}

document.addEventListener('DOMContentLoaded', function () {
  const userIcon = document.getElementById('userDetails')
  const userPopup = document.getElementById('userDetailsPopup')

  // User details to be populated
  const userDetails = {
    name: userSession,
    role: userLevel,
    avatarUrl: './img/dp.png'
  }

  // Event listeners for hover
  userIcon.addEventListener('mouseenter', function (e) {
    // Position the popup near the user icon
    const rect = userIcon.getBoundingClientRect()
    userPopup.style.top = `${rect.bottom + window.scrollY + 10}px`
    userPopup.style.left = `${rect.left + window.scrollX}px`

    // Populate user details
    document.getElementById('userName').textContent = userDetails.name
    document.getElementById('userDetails').textContent = userDetails.name
    document.getElementById('userRole').textContent = userDetails.role
    document.getElementById('userAvatar').src = userDetails.avatarUrl

    // Show popup
    userPopup.style.display = 'block'
  })

  userIcon.addEventListener('mouseleave', function () {
    // Hide popup
    userPopup.style.display = 'none'
  })
})

document.getElementById('eventModalClose').addEventListener('click', () => {
  closeActionModal()
})

document.getElementById('actionXbtn').addEventListener('click', () => {
  closeActionModal()
})

function closeActionModal () {
  var modalElement = document.getElementById('detaiTableModal')

  // Clear all input fields inside the modal except 'salesRep'
  var inputs = modalElement.querySelectorAll('input, textarea, select')
  inputs.forEach(input => {
    // Skip clearing the 'salesRep' field
    if (input.id === 'salesRep') {
      return // Skip this input
    }

    // Reset fields based on their type
    if (input.type === 'checkbox' || input.type === 'radio') {
      input.checked = false // Uncheck checkboxes and radio buttons
    } else {
      input.value = '' // Clear text inputs, textareas, and select dropdowns
    }
  })

  // Hide the modal
  modalElement.classList.remove('show')
  modalElement.style.display = 'none'
  document.body.classList.remove('modal-open') // Remove modal-open class

  // Remove modal backdrop if it exists
  document
    .querySelectorAll('.modal-backdrop')
    .forEach(backdrop => backdrop.remove())

  // Dispatch the hidden event
  var event = new Event('hidden.bs.modal')
  modalElement.dispatchEvent(event)

  // console.log("Modal hidden, fields cleared except 'salesRep'.");
}

document
  .getElementById('mainTableModal')
  .addEventListener('hidden.bs.modal', function () {
    // Reset the form inside the modal
    const form = this.querySelector('form')
    if (form) form.reset()

    //  console.log('Close button clicked');

    // Reset Select2 elements
    $('#dealStage').val(null).trigger('change') // Clear deal stage dropdown
    $('#dealCustomer').val(null).trigger('change') // Clear customer dropdown
    $('#dealPartner').val(null).trigger('change') // Clear partner dropdown
    $('#dealPartRep').val(null).trigger('change') // Clear part rep dropdown
  })

document.addEventListener('DOMContentLoaded', function () {
  //Control Search bar according to tab change

  const tabButtons = document.querySelectorAll('#myTab .nav-link')
  const searchAreas = document.querySelectorAll('.search-area')
  searchAreas.forEach(area => {
    area.style.display = 'none'
    document.getElementById('dashboardSearchArea').style.display = 'flex'
  })
  // Add event listener to each tab
  tabButtons.forEach(tab => {
    tab.addEventListener('shown.bs.tab', function (event) {
      // Hide all search areas first
      searchAreas.forEach(area => {
        area.style.display = 'none'
      })

      // Show the corresponding search area based on the active tab
      if (event.target.id === 'dashboard-tab') {
        document.getElementById('dashboardSearchArea').style.display = 'flex'
      } else if (event.target.id === 'table-tab') {
        document.getElementById('dealsTableSearchArea').style.display = 'flex'
      } else if (event.target.id === 'detailTable-tab') {
        document.getElementById('dealEventsSearchArea').style.display = 'flex'
      } else if (event.target.id === 'report-tab') {
        document.getElementById('partnerLoockupSearchArea').style.display =
          'flex'
      }
    })
  })

  // initiallly declare the select to for stage & partner

  $('#dealPartRep').select2({
    placeholder: 'Select Partner',
    allowClear: true,
    width: '100%',
    dropdownParent: $('#mainTableModal') // Fixes dropdown positioning
  })

  $('#dealStage').select2({
    placeholder: 'Select Stage',
    allowClear: true,
    width: '100%',
    dropdownParent: $('#mainTableModal') // Fixes dropdown positioning
  })
  const tabLinks = document.querySelectorAll('.nav-link') // Select all tabs
  const tabStorageKey = 'activeTab' // Key for localStorage

  // Restore the last active tab
  const activeTabId = localStorage.getItem(tabStorageKey)
  if (activeTabId) {
    const activeTab = document.getElementById(activeTabId)
    if (activeTab) {
      activeTab.click() // Activate the last selected tab
    }
  }

  // Save the active tab on click
  tabLinks.forEach(tab => {
    tab.addEventListener('click', function () {
      localStorage.setItem(tabStorageKey, this.id) // Save the clicked tab's ID
    })
  })
})

function showDetailTableTab () {
  // Hide all other tab panes
  $('.tab-pane').removeClass('show active')

  // Show the specific tab
  $('#detaiTableTab').addClass('show active')

  const detailTableTab = document.getElementById('detailTable-tab')
  if (detailTableTab) {
    detailTableTab.click()
  } else {
    console.error('Detail Table tab not found!')
  }
}

//DashBoard search bar for search all partners

function commonSearch (data) {
  const searchInput = document.getElementById('dashboardTableSearch')
  const searchResults = document.getElementById('searchResults')

  searchInput.addEventListener('input', function () {
    const searchText = this.value.toLowerCase().trim()

    // Filter only on specific fields
    const filteredResults = searchText
      ? data.filter(item =>
          // Check only customer, partner, and description fields
          ['customer', 'partner'].some(
            field =>
              item[field] &&
              item[field].toString().toLowerCase().includes(searchText)
          )
        )
      : []

    displayResults(filteredResults)
  })

  // Close results when clicking outside
  document.addEventListener('click', function (event) {
    if (!event.target.closest('.search-container')) {
      searchResults.style.display = 'none'
    }
  })
}

function displayResults (results) {
  // Clear previous results
  searchResults.innerHTML = ''

  // If no results or no search text, hide results
  if (results.length === 0) {
    searchResults.style.display = 'none'
    return
  }

  // Create table for results
  const table = document.createElement('table')
  table.className = 'table table-striped table-hover table-sm bg-dark  '

  // Create table header
  const thead = document.createElement('thead')
  thead.innerHTML = `
        <tr>
            <th>Sales Rep</th>
            <th>Customer</th>
            <th>Description</th>
            <th>Partner</th>
            <th>Partner Rep</th>
            <th>Stage</th>
        </tr>
    `
  table.appendChild(thead)

  // Create table body
  const tbody = document.createElement('tbody')
  results.forEach(item => {
    const row = document.createElement('tr')
    row.innerHTML = `
            <td>${item['salesRep']}</td>
            <td>${item['customer']}</td>
            <td>${item['description']}</td>
            <td>${item['partner']}</td>
            <td>${item['partnerRep']}</td>
            <td>${item['stage']}</td>
        `
    tbody.appendChild(row)
  })
  table.appendChild(tbody)

  // Clear and append table to results
  searchResults.innerHTML = ''
  searchResults.appendChild(table)
  searchResults.style.display = 'block'
}

// control gpArea display according to the type

document.getElementById('type').addEventListener('change', function () {
  // console.log(this.value); // Correct way to get the selected value
  if (this.value == 'Quote' || this.value == 'Invoice') {
    document.getElementById('gpArea').hidden = false
  } else {
    document.getElementById('gpArea').hidden = true
  }
})

const circularMenu = document.getElementById('circularMenu')
const reportTabContent = document.getElementById('report-tabContent')

circularMenu.addEventListener('click', () => {
  circularMenu.classList.toggle('active')
})

function switchTab (tabId) {
  // Hide all tabs within report-tabContent
  reportTabContent.querySelectorAll('.tab-pane').forEach(tab => {
    tab.classList.remove('active')
    tab.style.display = 'none'
  })

  // Show selected tab
  const selectedTab = reportTabContent.querySelector(`#${tabId}`)
  if (selectedTab) {
    selectedTab.classList.add('active')
    selectedTab.style.display = 'block'
  }

  // Close circular menu
  circularMenu.classList.remove('active')
}
function populateDealNumber (crmData) {
  // console.log('crmData:', crmData)
  document.getElementById('multyTender').addEventListener('input', function () {
    const enteredNumber = this.value.trim()
    const matchedCrmData = crmData.find(data => data.id === enteredNumber)
    const descriptionTextarea = document.getElementById(
      'multyTenderDescription'
    )
    if (matchedCrmData) {
      console.log('Description:', matchedCrmData.description)
      descriptionTextarea.value = matchedCrmData.description
    } else {
      console.log(
        'No matching CRM data found for entered number:',
        enteredNumber
      )
      descriptionTextarea.value = '' // Clear the textarea if no match is found
    }
  })
}


// -------------------------------------------Poplate 

function populateGp(crmItems) {
  const gpYearSelect = document.getElementById('gpYear');

  function filterDataByYear(year) {
    const filteredData = crmItems.filter(item => {
      const itemYear = new Date(item.gpMonth).getFullYear();
      return itemYear === year && (item.type === 'Quote' || item.type === 'Invoice');
    });

    const now = new Date();
    const thisMonth = now.toISOString().slice(0, 7);

    const lastMonthDate = new Date(now);
    lastMonthDate.setMonth(now.getMonth() - 1);
    const lastMonth = lastMonthDate.toISOString().slice(0, 7);

    const currentMonthIndex = now.getMonth();
    const lastMonthIndex = (currentMonthIndex - 1 + 12) % 12;

    const allMonths = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
    ];

    const otherMonthsHeaders = allMonths.filter((month, index) => {
      return index !== currentMonthIndex && index !== lastMonthIndex;
    });

    const mapData = filteredData.map(item => {
      let thisMonthGp = item.gpMonth === thisMonth ? item.gp : 0;
      let lastMonthGp = item.gpMonth === lastMonth ? item.gp : 0;

      const otherMonthsData = otherMonthsHeaders.map(month => {
        const monthIndex = allMonths.indexOf(month);
        let computedDate = new Date(year, monthIndex);
        const monthYear = computedDate.toISOString().slice(0, 7);
        return item.gpMonth === monthYear ? item.gp : 0;
      });

      return [
        item.salesRep.charAt(0).toUpperCase() + item.salesRep.slice(1),
        item.partner,
        item.customer,
        thisMonthGp,
        lastMonthGp,
        ...otherMonthsData
      ];
    });

    // Calculate totals for each month
    const monthlyTotals = mapData.reduce((totals, row) => {
      // Start from index 3 since first 3 columns are REP, PARTNER, END-CUST
      for (let i = 3; i < row.length; i++) {
        totals[i] = (totals[i] || 0) + Number(row[i]);
      }
      return totals;
    }, []);

    // Create total row with proper formatting
    const totalRow = [
      'TOTAL',
      '',
      '',
      ...monthlyTotals.slice(3).map(total => Number(total.toFixed(2)))
    ];

    // Add total row to the data
    mapData.push(totalRow);

    const headers = [
      'REP',
      'PARTNER',
      'END-CUST',
      'THIS MONTH',
      'LAST MONTH',
      ...otherMonthsHeaders
    ];

    function customRenderer(instance, td, row, col, prop, value, cellProperties) {
      const defaultRenderer = Handsontable.renderers.getRenderer(cellProperties.type) || Handsontable.renderers.TextRenderer;
      defaultRenderer.apply(this, arguments);

      const rowData = instance.getDataAtRow(row);
      const isLastRow = row === instance.countRows() - 1;

      if (isLastRow) {
        // Styling for total row
        td.style.backgroundColor = '#E3F2FD';
        td.style.fontWeight = 'bold';
        return td;
      }

      const monthData = rowData.slice(3);
      const hasAnyGP = monthData.some(val => Number(val) !== 0);

      if (!hasAnyGP) {
        td.style.backgroundColor = '#FFCDD2';
      } else {
        if (col === 3 && Number(rowData[3]) !== 0) {
          td.style.backgroundColor = '#B3E5FC';
        } else if (col === 4 && Number(rowData[4]) !== 0) {
          td.style.backgroundColor = '#C8E6C9';
        } else {
          td.style.backgroundColor = '';
        }
      }

      return td;
    }

    const container = document.querySelector('#gpTable');

    const hot = new Handsontable(container, {
      data: mapData,
      colHeaders: headers,
      rowHeaders: true,
      height: 'calc(100vh - 200px)',
      width: 'auto',
      licenseKey: 'non-commercial-and-evaluation',
      stretchH: 'all',
      autoWrapRow: true,
      columnSorting: true,
      filters: true,
      dropdownMenu: true,
      contextMenu: true,
      minSpareRows: 0, // Changed from 1 to 0 since we have a total row
      columns: [
        { type: 'text' },
        { type: 'text' },
        { type: 'text' },
        {
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: 'en-US'
          }
        },
        {
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: 'en-US'
          }
        },
        ...Array(otherMonthsHeaders.length).fill({
          type: 'numeric',
          numericFormat: {
            pattern: '0,0.00',
            culture: 'en-US'
          }
        })
      ],
      cells: function (row, col, prop) {
        const cellProperties = {};
        cellProperties.renderer = customRenderer;
        return cellProperties;
      },
      fixedColumnsStart: 3,
      manualColumnResize: true,
      manualRowResize: true
    });
  }

  const currentYear = new Date().getFullYear();
  filterDataByYear(currentYear);

  gpYearSelect.addEventListener('change', function () {
    const selectedYear = parseInt(this.value, 10);
    filterDataByYear(selectedYear);
  });
}
//-------------------------------------------------- daily performance report---------------------------------

function dailyPerformanceDeal (crmData) {
  // Sort the data by the `date` field in descending order
  crmData.sort((a, b) => {
    const dateA = new Date(a.date)
    const dateB = new Date(b.date)

    // Handle invalid dates by treating them as the earliest possible
    if (isNaN(dateA)) return 1
    if (isNaN(dateB)) return -1

    return dateB - dateA
  })

  // Define column headers and their display properties
  const columnsConfig = [
    { data: 'id', title: 'DEAL No', width: 60 },
    { data: 'date', title: 'DATE', width: 60 },
    { data: 'salesRep', title: 'REP', width: 55 },
    { data: 'customer', title: 'CUSTOMER', width: 150 },
    { data: 'partner', title: 'PARTNER', width: 150 },
    { data: 'description', title: 'DESCRIPTION', width: 250 },
    { data: 'stage', title: 'STAGE', width: 50 },
    { data: 'updated_at', title: 'LAST UPDATE', width: 100 }
  ]

  // Get the container element for Handsontable
  const container = document.getElementById('table1')

  // Initialize Handsontable
  const hot = new Handsontable(container, {
    data: crmData, // Pass the sorted crmData directly as an array of objects
    colHeaders: columnsConfig.map(col => col.title), // Use the titles for headers
    rowHeaders: false, // Disable row headers
    columns: columnsConfig, // Set column configurations
    height: '100%', // Make table responsive
    width: '100%',
    licenseKey: 'non-commercial-and-evaluation',
    stretchH: 'all', // Stretch columns to fill the container
    readOnly: true, // Make the table read-only
    afterOnCellDblClick: function (event, coords, td) {
      const rowData = this.getSourceDataAtRow(coords.row)
      console.log('Double-clicked row data:', rowData)
    }
  })

  // Update total deals count
  function updateTotalDealsCount (filteredData) {
    const totalDeals = filteredData.length // Calculate the total deals
    document.getElementById('dailyTotalDeal').textContent = totalDeals // Update the display
  }

  // Variables to track selected filters
  let selectedDate = null
  let selectedRep = null

  // Function to filter data based on the selected filters
  function filterData () {
    let filteredData = crmData

    if (selectedDate) {
      filteredData = filteredData.filter(item => item.date === selectedDate)
    }

    if (selectedRep) {
      filteredData = filteredData.filter(item => item.salesRep === selectedRep)
    }

    hot.loadData(filteredData) // Update Handsontable data
    updateTotalDealsCount(filteredData) // Update total deals display
  }

  // Event listener for date input
  const performanceDateInput = document.getElementById('performanceDate')
  performanceDateInput.addEventListener('change', function () {
    selectedDate = this.value // Update selected date
    filterData() // Apply filters
  })

  // Event listener for sales rep selection using Select2
  $('#performanceRep').on('select2:select', function (e) {
    selectedRep = e.params.data.id // Update selected rep
    filterData() // Apply filters
  })

  // Load initial data based on the preselected date and sales rep
  const initialDate = performanceDateInput.value
  if (initialDate) {
    selectedDate = initialDate
  }
  filterData() // Apply filters initially

  return hot
}

function dailyPerformanceDealActions (crmItems) {
  // console.log('crmItems', crmItems)

  // Sample data for second table (rearranging columns and removing unneeded ones)
  const data2 = crmItems.map(item => [
    item.trnNo, // TRN No
    item.date, // Date
    item.salesRep, // Sales Rep
    item.customer, // Customer
    item.partner, // Partner
    item.action, // Action
    item.model, // Model
    item.fupUser, // Fup User
    item.followUp, // Follow Up
    item.fupAction, // Fup Action
    item.updated_at // Updated At
  ])

  // Get container for second table
  const container2 = document.getElementById('table2')

  // Initialize Handsontable for the second table with fixed column widths
  const hot2 = new Handsontable(container2, {
    data: data2,
    rowHeaders: false, // Disable row headers
    colHeaders: [
      'TRN No',
      'DATE',
      'REP',
      'CUSTOMER',
      'PARTNER',
      'ACTION',
      'MODEL',
      'FUP-USER',
      'FUP-DATE',
      'FUP ACTION',
      'LAST UPDATE'
    ], // Column headers for selected fields
    columns: [
      { width: 60 }, // TRN No
      { width: 80 }, // Date
      { width: 80 }, // Sales Rep
      { width: 150 }, // Customer
      { width: 150 }, // Partner
      { width: 150 }, // Action
      { width: 80 }, // Model
      { width: 80 }, // Fup User
      { width: 80 }, // Follow Up
      { width: 150 }, // Fup Action
      { width: 100 } // Updated At
    ], // Define fixed width for each selected column
    height: '100%',
    width: '100%',
    licenseKey: 'non-commercial-and-evaluation',
    stretchH: 'all',
    fixedColumnsLeft: 2 // Optional: Keep the first two columns fixed while scrolling horizontally
  })

  // Variables to track selected filters for date and sales rep
  let selectedDate = null
  let selectedRep = null

  // Function to filter data based on the selected filters
  function filterData () {
    let filteredData = crmItems

    if (selectedDate) {
      filteredData = filteredData.filter(item => item.date === selectedDate)
    }

    if (selectedRep) {
      filteredData = filteredData.filter(item => item.salesRep === selectedRep)
    }

    // console.log('Filtered Data:', filteredData)

    // Update Handsontable data with the filtered result
    const filteredDataForTable = filteredData.map(item => [
      item.id, // TRN No
      item.date, // Date
      item.salesRep, // Sales Rep
      item.customer, // Customer
      item.partner, // Partner
      item.action, // Action
      item.model, // Model
      item.fupUser, // Fup User
      item.followUp, // Follow Up
      item.fupAction, // Fup Action
      item.updated_at // Updated At
    ])

    hot2.loadData(filteredDataForTable) // Apply filtered data to the table

    // Calculate the total deal actions (the total number of rows after filtering)
    const totalDealActions = filteredData.length

    // Update the total inside the div
    document.getElementById('dailyTotalDealActions').innerHTML =
      totalDealActions
  }

  // Event listener for date input (same as in the first table)
  const performanceDateInput = document.getElementById('performanceDate')
  performanceDateInput.addEventListener('change', function () {
    selectedDate = this.value // Update selected date
    filterData() // Apply filters
  })

  // Event listener for sales rep selection (same as in the first table)
  $('#performanceRep').on('select2:select', function (e) {
    selectedRep = e.params.data.id // Update selected rep
    filterData() // Apply filters
  })

  // Load initial data based on preselected date and sales rep (if any)
  const initialDate = performanceDateInput.value
  if (initialDate) {
    selectedDate = initialDate
  }
  filterData() // Apply filters initially

  return hot2
}
