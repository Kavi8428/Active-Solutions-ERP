 // Sample stock data with extended information
 let stockData = [{
    id: 1,
    item_code: 'DS224+',
    qty: 50,
    rQty: 10,
    rRep: 'John Doe',
    aQty: 40,
    updated_at: '2024-01-15',
    serials: [{
            id: 1,
            eserial: 'SN001',
            status: 'Available',
            reserved_by: null
        },
        {
            id: 2,
            eserial: 'SN002',
            status: 'Reserved',
            reserved_by: 'Jane Smith'
        }
    ]
},
{
    id: 2,
    item_code: 'HAT3100',
    qty: 100,
    rQty: 20,
    rRep: 'Alice Johnson',
    aQty: 80,
    updated_at: '2024-02-20',
    serials: [{
            id: 3,
            eserial: 'SN003',
            status: 'Available',
            reserved_for: null
        },
        {
            id: 4,
            eserial: 'SN004',
            status: 'Reserved',
            reserved_for: 'Bob Wilson'
        }
    ]
}
];

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
// Initialize Stock Grid
const stockGridContainer = document.getElementById('stockGrid');
const serialGridContainer = document.getElementById('serialNumbersGrid');
const addStockBtn = document.getElementById('addStockBtn');
const serialNumbersModal = new bootstrap.Modal(document.getElementById('serialNumbersModal'));
const currentItemCodeSpan = document.getElementById('currentItemCode');

// Stock Grid Configuration
const stockHot = new Handsontable(stockGridContainer, {
    data: stockData,
    columns: [{
            data: 'item_code',
            title: 'Item Code',
            type: 'text'
        },
        {
            data: 'qty',
            title: 'Total Quantity',
            type: 'numeric'
        },
        {
            data: 'rQty',
            title: 'Reserved Quantity',
            type: 'numeric'
        },
        {
            data: 'rRep',
            title: 'Reserved Rep',
            type: 'text'
        },
        {
            data: 'aQty',
            title: 'Available Qty',
            type: 'text'
        },
        {
            data: 'updated_at',
            title: 'Last Modified',
            type: 'date',
            dateFormat: 'YYYY-MM-DD'
        }
    ],
    rowHeaders: true,
    colHeaders: true,
    height: 400,
    width: '100%',
    stretchH: 'all',
    contextMenu: {
        items: {
            'view_serials': {
                name: 'View Serial Numbers',
                callback: function(key, selection) {
                    const selectedRow = selection[0].start.row;
                    const selectedStock = stockData[selectedRow];

                    // Update modal title
                    currentItemCodeSpan.textContent = selectedStock.item_code;

                    // Initialize Serial Numbers Grid
                    const serialHot = new Handsontable(serialGridContainer, {
                        data: selectedStock.serials,
                        columns: [{
                                data: 'eserial',
                                title: 'Serial Number',
                                type: 'text'
                            },
                            {
                                data: 'status',
                                title: 'Status',
                                type: 'text',
                                renderer: function(instance, td, row, col, prop, value) {
                                    td.innerHTML = value;
                                    td.className = value === 'Available' ?
                                        'serial-status-available' :
                                        'serial-status-reserved';
                                }
                            },
                            {
                                data: 'reserved_by',
                                title: 'Reserved By',
                                type: 'text'
                            }
                        ],
                        rowHeaders: true,
                        colHeaders: true,
                        contextMenu: {
                            items: {
                                'toggle_status': {
                                    name: 'Toggle Status',
                                    callback: function(key, selection) {
                                        const selectedSerialRow = selection[0].start.row;
                                        const selectedSerial = selectedStock.serials[selectedSerialRow];

                                        // Toggle status and reserved_by
                                        if (selectedSerial.status === 'Available') {
                                            selectedSerial.status = 'Reserved';
                                            selectedSerial.reserved_by = 'System User';
                                        } else {
                                            selectedSerial.status = 'Available';
                                            selectedSerial.reserved_by = null;
                                        }

                                        // Refresh the serial numbers grid
                                        this.render();
                                    }
                                }
                            }
                        }
                    });

                    // Show the modal
                    serialNumbersModal.show();
                }
            },
            'edit_stock': {
                name: 'Edit Stock',
                callback: function(key, selection) {
                    const selectedRow = selection[0].start.row;
                    const selectedStock = stockData[selectedRow];

                    // Prompt for editing (you could replace this with a more sophisticated modal)
                    const newQty = prompt('Enter new total quantity:', selectedStock.qty);
                    const newRQty = prompt('Enter new reserved quantity:', selectedStock.rQty);
                    const newRRep = prompt('Enter new reserved representative:', selectedStock.rRep);

                    if (newQty !== null) selectedStock.qty = parseInt(newQty);
                    if (newRQty !== null) selectedStock.rQty = parseInt(newRQty);
                    if (newRRep !== null) selectedStock.rRep = newRRep;

                    // Refresh the stock grid
                    stockHot.render();
                }
            },
            'remove_stock': {
                name: 'Remove Stock',
                callback: function(key, selection) {
                    const selectedRow = selection[0].start.row;
                    stockData.splice(selectedRow, 1);
                    stockHot.render();
                }
            }
        }
    },
    licenseKey: 'non-commercial-and-evaluation'
});

// Add Stock Button Event
addStockBtn.addEventListener('click', () => {
    const newStock = {
        id: stockData.length + 1,
        item_code: prompt('Enter Item Code:'),
        qty: parseInt(prompt('Enter Total Quantity:')),
        rQty: parseInt(prompt('Enter Reserved Quantity:')),
        rRep: prompt('Enter Reserved Representative:'),
        created_at: new Date().toISOString().split('T')[0],
        serials: []
    };

    if (newStock.item_code) {
        stockData.push(newStock);
        stockHot.render();
    }
});
});