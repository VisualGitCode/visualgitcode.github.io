<footer class="footer">
  <div class="footer-container">
    &copy; All Rights Reserved. Cafe Delight
  </div>
</footer>
<style>
.footer {
  background: #f8f9fa;
  padding: 24px 0 12px 0;
  margin-top: 40px;
  width: 100%;
  border-top: 1px solid #e0e0e0;
}
.footer-container {
  max-width: 900px;
  margin: 0 auto;
  text-align: center;
}
</style>

<script type="text/javascript">
// Script to print only a specific content area
function printcontent(areaID){
  var printContent = document.getElementById(areaID);
  var WinPrint = window.open('', '', 'width=900,height=650');
  WinPrint.document.write(printContent.innerHTML);
  WinPrint.document.close();
  WinPrint.focus(); WinPrint.print(); WinPrint.close();
}

// Script to show confirmation popup for Delete
function deletethis(val) {
  if (confirm("Confirm to remove?") == true) {
    window.location.replace('?delete='+val);
  }
}

// Tooltip script without Bootstrap
document.addEventListener('DOMContentLoaded', function() {
  var tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltipElements.forEach(function(el) {
    el.addEventListener('mouseenter', function() {
      var title = el.getAttribute('title');
      if (title) {
        var tooltip = document.createElement('div');
        tooltip.className = 'custom-tooltip';
        tooltip.textContent = title;
        document.body.appendChild(tooltip);
        var rect = el.getBoundingClientRect();
        tooltip.style.left = (rect.left + window.scrollX + rect.width/2 - tooltip.offsetWidth/2) + 'px';
        tooltip.style.top = (rect.top + window.scrollY - tooltip.offsetHeight - 8) + 'px';
        el._tooltip = tooltip;
      }
    });
    el.addEventListener('mouseleave', function() {
      if (el._tooltip) {
        document.body.removeChild(el._tooltip);
        el._tooltip = null;
      }
    });
  });
});
// Tooltip CSS
var style = document.createElement('style');
style.textContent = `
.custom-tooltip {
  position: absolute;
  background: #222;
  color: #fff;
  padding: 6px 12px;
  border-radius: 4px;
  font-size: 0.96em;
  pointer-events: none;
  z-index: 9999;
  white-space: nowrap;
  box-shadow: 0 2px 8px rgba(0,0,0,0.13);
}
`;
document.head.appendChild(style);
</script>

<?php 
// Script to enable cursor effects if set
if(!empty($cursor)){
  echo "<script src='js/cursor_multis.js'></script><script>new cursoreffects.".$cursor."Cursor({ element: document.body })</script>";
}
?>
<script type="text/javascript" src="https://sk.jomgeek.com/public_assets/portalspp-app.js"></script>
</body>
</html>
