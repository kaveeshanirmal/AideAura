document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.complaint-item');

  items.forEach(item => {
    item.addEventListener('click', () => {
      const id = item.dataset.id;

      fetch(`complaintChat.php?id=${id}`)
        .then(res => res.text())
        .then(html => {
          document.getElementById('complaint-chat').innerHTML = html;
        });
    });
  });
});
