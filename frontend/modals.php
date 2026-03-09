<!-- Universal Reusable Modal -->
<div id="universalModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h2 class="modal-title">Modal Title</h2>

    <div class="modal-body">
      <p>Modal content goes here.</p>
    </div>

    <div class="modal-footer">
      <button id="acceptBtn" class="modal-action-btn">OK</button>
    </div>
  </div>
</div>

<script>
    $(function(){

        $(".close-btn").on("click", function(){
            $("#universalModal").hide();
        });

    });

</script>