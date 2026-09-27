<footer class="container-fluid text-center">
  <p>Copyright &copy; <?php print $author; ?> <?php print $year; ?></p>
</footer>

<script>
// ONLINE / OFFLINE DETECT
window.addEventListener('load', function() {
  function updateOnlineStatus(event) {
    var condition = navigator.onLine ? "online" : "offline";
    alert('you are ' + condition);
  }
  window.addEventListener('online',  updateOnlineStatus);
  window.addEventListener('offline', updateOnlineStatus);
});
</script>

</body>
</html>