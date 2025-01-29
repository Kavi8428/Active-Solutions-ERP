document.addEventListener('DOMContentLoaded', function() {


    
    const urlParams = new URLSearchParams(window.location.search)
      const urlId = urlParams.get('id')
      const urlInv = urlParams.get('invNo')
      if (urlId) {
        populateGeneralData({ urlId: urlId })
      } else if (urlInv) {
        populateGeneralData({ urlInv: urlInv })
      } else {
        populateGeneralData();
      }
     

    fetchItemCodes();
    setGrnNo();
 

});
let stock = [];
let array = [];
let itemCodes = [];
let invoiceData = [];
let invoiceItems = [];

function setGrnNo() {
    fetch('../../functions/fetchGrnData.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Grn Data Is Not Responding');
        }
        return response.json();
    })
    .then(response => {
        //console.log('GRN response', response);
        
        // Use reduce to find the maximum id
        const lastId = response.reduce((max, x) => Math.max(max, x.id), -Infinity);

        if(lastId){
            document.getElementById('id'). value= lastId+1;
        }
        else{
            showToast('Please contact developer');
            console.error('The Id field is empty in data base');
        }        
        //console.log('lastId', lastId);
    })
    .catch(error => {
        console.error('Error occurred while fetching grn data', error);
    });

}
    


function showToast(message, duration = 5000) {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'show';
    setTimeout(() => {
        toast.className = toast.className.replace('show', '');
    }, duration);
}



function fetchItemCodes() {
    fetch('../../functions/fetchItems.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Stock is not responding');
            }
            return response.json();
        })
        .then(response => {
            // console.log('Stock', response);
            itemCodes = response.map(item => item.item_code);
            itemCodes.unshift(''); // Add 'SELECT' to the 0 index of itemCodes array
            populateHandsonTable(); // Call populateHandsonTable after itemCodes is populated
        })
        .catch(error => {
            console.error('Stock Fetch is not working', error);
        });
}


function fetchInvoiceData() {
    fetch('../../functions/fetchInvoice.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Fetch Invoice Items Response not ok (${response.status} ${response.statusText})`);
            }
            return response.json();
        })
        .then(data => {
             console.log('Fetched Invoice Data:', data);
             invoiceData = data; // Update the global invoiceItems object with the fetched data
            // Now you can use invoiceItems after this point
            // console.log('Updated invoiceItems object:', invoiceItems);
        })
        .catch(error => {
            console.error('Failed to fetch Invoice Items.', error);
        });
}

fetchInvoiceData() 

function fetchInvoiceItems() {
    fetch('../../functions/fetchInvoiceItems.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Fetch Invoice Items Response not ok (${response.status} ${response.statusText})`);
            }
            return response.json();
        })
        .then(data => {
             console.log('Fetched Invoice Items:', data);
            invoiceItems = data; // Update the global invoiceItems object with the fetched data
            // Now you can use invoiceItems after this point
            // console.log('Updated invoiceItems object:', invoiceItems);
        })
        .catch(error => {
            console.error('Failed to fetch Invoice Items.', error);
        });
}

fetchInvoiceItems() 




let hot; // Global variable to hold the Handsontable instance

function getInitialData() {
    const savedData = localStorage.getItem('handsontableData'); // Get saved data from localStorage
    if (savedData) {
        return JSON.parse(savedData); // Return parsed JSON data if found
    } else {
        // Return an array of 10 empty rows if no data is saved
        return Array(10).fill().map(() => ['', '', '', '', '', '']);
    }
}

const removedRows = [];

function populateHandsonTable() {
    // Custom tag input renderer for SERIALS column
    function tagRenderer(instance, TD, row, col, prop, value, cellProperties) {
        TD.innerHTML = ''; // Clear previous content in the cell

        if (prop === 'serials') {
            const tagInput = document.createElement('div');
            tagInput.className = 'tagInput';

            const tags = value ? value.split(',').filter(tag => tag.trim()) : [];
            tags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'tag';
                tagElement.innerHTML = `
                    ${tag.trim()}
                    <span class="remove">×</span>
                `;

                tagElement.querySelector('.remove').addEventListener('click', (e) => {
                    e.stopPropagation();
                    const newTags = tags.filter(t => t !== tag);
                    instance.setDataAtCell(row, col, newTags.join(','));
                });

                tagInput.appendChild(tagElement);
            });

            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = 'Add Serial...';

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const newTag = input.value.trim();
                    if (newTag) {
                        const currentValue = instance.getDataAtCell(row, col) || '';
                        const newValue = currentValue ? `${currentValue},${newTag}` : newTag;
                        instance.setDataAtCell(row, col, newValue);
                        input.value = '';
                    }
                }
            });

            tagInput.appendChild(input);
            TD.appendChild(tagInput);
            tagInput.style.width = `${TD.offsetWidth}px`;
        } else {
            Handsontable.renderers.TextRenderer.apply(this, arguments);
        }

        return TD;
    }

    Handsontable.renderers.registerRenderer('tag', tagRenderer);

    const container = document.getElementById('invTable');
    hot = new Handsontable(container, { // Initialize Handsontable globally
        data: getInitialData(),
        colHeaders: ['INDEX', 'ITEM', 'QUANTITY', 'SERIALS', 'DESCRIPTION'],
                columns: [
            {
                data: 'index',
                type: 'numeric',
                visible: false ,// This will hide the index column
                hidden: true // This will hide the index column
            },
            {
                data: 'itemCode',
                type: 'dropdown',
                source: itemCodes, // Replace with your item codes array
                width: 100
            },
            {
                data: 'quantity',
                type: 'numeric',
                width: 30
            },
            {
                data: 'serials',
                renderer: 'tag',
                type: 'text',
                width: 200
            },
            {
                data: 'description',
                type: 'text',
                width: 100
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
        hiddenColumns: {
            columns: [0], // Index of the column to hide (INDEX column in this case)
            indicators: false, // Set to true if you want a small indicator for hidden columns
        },
        afterChange: function(changes, source) {
            if (source !== 'loadData') {
                const currentData = hot.getData();
                localStorage.setItem('handsontableData', JSON.stringify(currentData));
            }
        }
    });
    Handsontable.hooks.add('afterChange', function(changes, source) {
        if (source === 'edit') {
            const data = this.getData();
            data.forEach((row, index) => {
                if (row[1] && row[4]) {
                    const quantity = parseFloat(row[1]);
                    const price = parseFloat(row[4]);
                    const amount = quantity * price;
                    this.setDataAtCell(index, 5, amount.toFixed(2), 'calculate');
                }
            });
        }
    });

    

    // Function to calculate the Total
    Handsontable.hooks.add('afterChange', function(changes, source) {
        if (source === 'edit' || source === 'calculate') {
            const data = this.getData();
            let total = 0;
            data.forEach(row => {
                if (row[5]) {
                    total += parseFloat(row[5]);
                }
            });
            //  console.log('Totals', total.toFixed(2));
        }
    });
    Handsontable.hooks.add('beforeRemoveRow', function(index, amount) {
        for (let i = index; i < index + amount; i++) {
            // Fetch the entire row data
            const rowData = hot.getDataAtRow(i);
            if (rowData) {
                removedRows.push(rowData);
            }
        }
        // console.log('Removed rows:', removedRows);
    });
}

// Function to populate GRN items into Handsontable
function populateGrnItems(selectedId) {
    
    console.log('selectedId', selectedId);
    
    fetch('../../functions/fetchGrnItem.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('There is an error with fetching GRN Items');
            }
            return response.json();
        })
        .then(response => {
            //console.log('response', response);

            const matchedData = response.filter(item => item.grn_id === selectedId);
            //console.log('matchedData', matchedData);

            if (matchedData.length > 0) {
                const newData = matchedData.map(item => ({
                    index : item.id,
                    itemCode: item.itemCode,
                    quantity: item.quantity,
                    serials: item.serial,
                    description: item.description,
                }));

                if (hot) {
                    hot.loadData(newData); // Use the global `hot` instance
                } else {
                    console.error('Handsontable instance is not initialized.');
                }
            } else {
                console.error('No matching invoice item found for GRN ID:', selectedId);
            }
        })
        .catch(error => {
            console.error('Unable to fetch GRN Items:', error);
        });
}



// Function to collect Handsontable data
function sendData() {
    // Create an object to store general data
    var formData = new FormData();

    var id = document.getElementById('id').value;
    var invNo = document.getElementById('invNo').value;
    var object = document.getElementById('object').value;
    var supplier = document.getElementById('supplier').value;
    var method = document.getElementById('method').value;
    var date = document.getElementById('date').value;

    // Front-end validation
    let isValid = true;
    const fields = [
        { value: id, element: document.getElementById('id') },
        { value: invNo, element: document.getElementById('invNo') },
        { value: object, element: document.getElementById('object') },
        { value: supplier, element: document.getElementById('supplier') },
        { value: method, element: document.getElementById('method') },
        { value: date, element: document.getElementById('date') }
    ];

    fields.forEach(field => {
        if (!field.value) {
            field.element.style.border = '2px solid red';
            isValid = false;
        } else {
            field.element.style.border = '';
        }
    });

    if (!isValid) {
        Swal.fire({
            toast: true,
                icon: 'error',
                title: 'Please fill in all required fields!',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
        });
        return; // Stop the function if validation fails
    }

    // Add general data to the FormData object
    formData.append('id', id);
    formData.append('supplier', supplier);
    formData.append('date', date);
    formData.append('method', method);
    formData.append('invNo', invNo);
    formData.append('object', object);

    // Access the Handsontable instance and get the table data
    const tableData = hot.getData();

    // Filter out rows with all null or empty values
    const filteredData = tableData.filter(row => {
        return row.some(cell => cell !== null && cell !== '');
    });

    // Column headers
    const columnHeaders =['INDEX', 'ITEM', 'QUANTITY', 'SERIALS', 'DESCRIPTION'];

    // Map filtered data to an array of objects using the column headers
    const grnItems = filteredData.map(row => {
        let rowObject = {};
        columnHeaders.forEach((header, index) => {
            rowObject[header] = row[index]; // Map the column header to the row value
        });
        return rowObject;
    });

    // Add items data to the FormData object
    var index = [];
    var itemCodes = [];
    var quantities = [];
    var serialNumbers = [];
    var description = [];
    let valid = true;

    grnItems.forEach(function(item) {
        const serialCount = item.SERIALS ? item.SERIALS.split(',').filter(tag => tag.trim()).length : 0;
        if (item.QUANTITY != serialCount) {
            valid = false;
        }
        index.push(item.INDEX); // Use the correct key for item code
        itemCodes.push(item.ITEM); // Use the correct key for item code
        quantities.push(item.QUANTITY); // Use the correct key for quantity
        serialNumbers.push(item.SERIALS); // Use the correct key for serials
        description.push(item.DESCRIPTION); // Use the correct key for description
    });

    if (!valid) {
        Swal.fire({
            toast: true,
            icon: 'error',
            title: 'Quantity and Serial count mismatch in one or more rows!',
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return; // Stop the function if validation fails
    }

    formData.append('index', JSON.stringify(index));
    formData.append('itemCode', JSON.stringify(itemCodes));
    formData.append('quantity', JSON.stringify(quantities));
    formData.append('serialNumbers', JSON.stringify(serialNumbers));
    formData.append('description', JSON.stringify(description));


    // Log the formData content for debugging
    for (var pair of formData.entries()) {
      // console.log(pair[0]+ ': ' + pair[1]); 
    }

    // AJAX request
    $.ajax({
        url: '../../functions/insertGRN.php', // Replace with your server endpoint
        type: 'POST',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Ensure the correct content type is set
        success: function(response) {
           // console.log('Form submitted successfully:', response);
            Swal.fire({
                toast: true,
                icon: 'success',
                title: response.message || 'Data saved successfully!',
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true
              }).then(() => {
                location.reload();
              });       
             },
        error: function(xhr, status, error) {
            console.log('Form submission failed:', error);
            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Form submission failed:', error,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true
              }).then(() => {
                location.reload();
              });
        }
    });
}

function populateGeneralData(urlValue) {
    console.log('urlValue top', urlValue);
    var id = document.getElementById('id');
    var invNo = document.getElementById('invNo');
    var object = document.getElementById('object');
    var supplier = document.getElementById('supplier');
    var method = document.getElementById('method');
    var date = document.getElementById('date');
    const searchInput = $('#search');


    
    fetch('../../functions/fetchGrnData.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error Occurred while fetching GrnData');
        }
        return response.json();
    })
    .then(response => {
        // Initialize Select2 for search input after receiving the data
        const loadingScreen = document.getElementById('loadingScreen')
        loadingScreen.style.display = 'flex'
        setTimeout(() => {
          loadingScreen.style.display = 'none'
        }, 1000)



        searchInput.select2({
            placeholder: 'Select INV',
            width: '100%',
            data: response.map(item => ({
                id: item.invNo,
                text: item.invNo
            }))
        });
        console.log('urlValue', urlValue);
        if (urlValue && urlValue.urlId) {
            
            invNo.value = urlValue.urlInv ? urlValue.urlInv : '';
            const matchedData = response.find(item => String(item.id).trim() === String(urlValue.urlId).trim());
            
            if (matchedData) {
                // Set values for other fields
                let grnId = matchedData.id;
                populateGrnItems({selectedGrnId : grnId}); 

                id.value = matchedData.id;
                invNo.value = matchedData.invNo;
                object.value = matchedData.object; // Set value for object dropdown
                supplier.value = matchedData.supplier;
                method.value = matchedData.method;
                date.value = matchedData.date;
                // Trigger change event for Select2 to update the UI
                $(object).trigger('change');
            } else {
                console.log('No match found');

            }
        }else if(urlValue && urlValue.urlInv){

            console.log('Invoice', urlValue.urlInv);
            invoiceData.forEach(item => {
                if (item.inv === urlValue.urlInv) {
                    console.log('Matched Item', item);
                    invNo.value = item.inv;
                    object.value = item.object;
                    supplier.value = item.customer;
                    date.value = item.inv_date;
                    let invId = item.id;
                    populateGrnItems({selectedInvId : invId});
                    $(object).trigger('change');
                }
            });

        }
        

        // Add event listener for selection
        searchInput.on('select2:select', function(e) {
            const selectedId = e.params.data.id;
            const matchedData = response.find(item => item.invNo === selectedId);
            if (matchedData) {
                // Set values for other fields
                let grnId = matchedData.id;
                populateGrnItems({selectedGrnId : grnId});

                id.value = matchedData.id;
                invNo.value = matchedData.invNo;
                object.value = matchedData.object; // Set value for object dropdown
                supplier.value = matchedData.supplier;
                method.value = matchedData.method;
                date.value = matchedData.date;

                // Trigger change event for Select2 to update the UI
                $(object).trigger('change');
            } else {
                console.log('No match found');
            }
        });

        // If urlValue is provided, select the corresponding option
       

    })
    .catch(error => {
        console.error('Error Occurred for fetching GRN Data', error);
    });
}




 








function submitForm() {
    var supplier = document.getElementById('supplier').value;
    var date = document.getElementById('date').value;
    var method = document.getElementById('method').value;
    var invNo = document.getElementById('invNo').value;
    var object = document.getElementById('object').value;

    var itemsData = getItemsArray();

    // Create a FormData object
    var formData = new FormData();

    // Add general data to the FormData object
    formData.append('supplier', supplier);
    formData.append('date', date);
    formData.append('method', method);
    formData.append('invNo', invNo);
    formData.append('object', object);

    // Add items data to the FormData object
    var itemCodes = [];
    var quantities = [];
    var serialNumbers = [];
    itemsData.forEach(function(item) {
        itemCodes.push(item.itemCode);
        quantities.push(item.quantity);
        serialNumbers.push(item.serialNumbers);
    });
    formData.append('itemCode', JSON.stringify(itemCodes));
    formData.append('quantity', JSON.stringify(quantities));
    formData.append('serialNumbers', JSON.stringify(serialNumbers));

    // Log the formData content for debugging
    for (var pair of formData.entries()) {
       // console.log(pair[0]+ ': ' + pair[1]); 
    }

    // AJAX request
    $.ajax({
        url: '../../functions/insertGRN.php', // Replace with your server endpoint
        type: 'POST',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Ensure the correct content type is set
        success: function(response) {
            console.log('Form submitted successfully:', response);
            // Handle success response
            Swal.fire({
                toast: true,
                icon: 'success',
                title: data.message || 'Data saved successfully!',
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
              }).then(() => {
                location.reload();
              });
        },
        error: function(xhr, status, error) {
            console.log('Form submission failed:', error);
            // Handle error response
        }
    });
}





