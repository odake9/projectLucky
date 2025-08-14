const signature = document.getElementById('signature');
const iframe1 = document.getElementById('signature-iframe');
const refreshing = document.getElementById('signature');
const iframe2 = document.getElementById('refreshing-iframe');
const iceblended = document.getElementById('signature');
const iframe3 = document.getElementById('iceblended-iframe');

  toggleLink.addEventListener('click', function(e) {
    e.preventDefault(); // prevent link navigation
    iframe.style.display = (iframe.style.display === 'none' || iframe.style.display === '') 
      ? 'block' 
      : 'none';
  });