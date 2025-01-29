function notification(countElement) {
const user = '<?php echo $_SESSION["user_name"]; ?>'; // Assuming PHP injects username

  fetch('../../functions/fetchQuotations.php') // Assuming PHP file name is fetchQuotations.php
    .then(response => response.json())
    .then(data => {
      // Filter data based on the user
      console.log('data',data)
      const filteredData = data.filter(item => item.pmanager === user && item.status === '');

      // Update the count in the HTML element
      countElement.textContent = filteredData.length;

      // Clear existing notifications
      const dropdownMenu = document.querySelector('.dropdown-menu');
      dropdownMenu.innerHTML = '';

      // Create and append new notifications
      filteredData.forEach((item, index) => {
        const listItem = document.createElement('li');
        listItem.classList.add('mb-2');

        const anchor = document.createElement('a');
        anchor.classList.add('dropdown-item', 'border-radius-md');
        anchor.href = 'javascript:;';

        const div = document.createElement('div');
        div.classList.add('d-flex', 'align-items-center', 'py-1');

        const spanIcon = document.createElement('span');
        spanIcon.classList.add('material-icons');
        spanIcon.textContent = 'email';

        const divContent = document.createElement('div');
        divContent.classList.add('ms-2');

        const header = document.createElement('h6');
        header.classList.add('text-sm', 'font-weight-normal', 'my-auto');
        header.textContent = 'Need your Approval';

        const table = document.createElement('table');
        table.classList.add('table', 'table-borderless');

        const thead = document.createElement('thead');
        const tr = document.createElement('tr');
        const tdDealId = document.createElement('td');
        tdDealId.textContent = item.deal_id;
        const tdDealName = document.createElement('td');
        tdDealName.textContent = item.deal_name;

        // Append elements
        thead.appendChild(tr);
        tr.appendChild(tdDealId);
        tr.appendChild(tdDealName);
        table.appendChild(thead);
        divContent.appendChild(header);
        divContent.appendChild(table);
        div.appendChild(spanIcon);
        div.appendChild(divContent);
        anchor.appendChild(div);
        listItem.appendChild(anchor);

        listItem.addEventListener('click', () => {
          console.log(`deal_id: ${item.deal_id}, deal_name: ${item.deal_name}`);
          window.location.href = '../dashboards/bom.php?code=' + item.deal_id;
        });


        dropdownMenu.appendChild(listItem);
        
      });
    })
    .catch(error => console.error('Error fetching data:', error));
}


const countElement = document.getElementById('count'); // Get the HTML element