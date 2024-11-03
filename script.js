const submit_btn = document.getElementById("submit");
const data_table = document.getElementById("data");

const MONTH_NAMES = {
  '01': 'January',
  '02': 'February',
  '03': 'March'
};

submit_btn.onclick = function (e) {
  e.preventDefault();
  data_table.style.display = "block";

  fetchDataAndDisplay()
      .then(response => {
        if (Object.values(response).length) {
          const table_body = data_table.querySelector('table tbody');
          table_body.innerHTML = '';

          // Add rows
          Object.values(response).forEach((item) => {
            const tr_table = document.createElement('tr');
            const month = document.createElement('td');
            month.textContent = MONTH_NAMES[item.month];
            const amount = document.createElement('td');
            amount.textContent = item.amount;
            tr_table.append(month, amount);
            table_body.appendChild(tr_table);
          })
        }
      })
      .catch(() => alert("Not implemented"));
};

async function fetchDataAndDisplay() {
  const user_id = document.getElementById('user').value;
  const response = await fetch('http://empty', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id })
  });

  return await response.json();
}
