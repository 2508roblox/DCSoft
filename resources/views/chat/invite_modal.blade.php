<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Invite members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="inviteForm" action="/chat/room/{room_id}/members/add" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Thêm danh sách người dùng vào đây -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal.fade .modal-dialog{transition:-webkit-transform .3s ease-out;transition:transform .3s ease-out;transition:transform .3s ease-out,-webkit-transform .3s ease-out;-webkit-transform:translate(0,-25%);transform:translate(0,-25%)}.modal.show .modal-dialog{-webkit-transform:translate(0,0);transform:translate(0,0)}.modal-dialog-centered{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;min-height:calc(100% - (.5rem * 2))}.modal-content{position:relative;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;width:100%;pointer-events:auto;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;outline:0}.modal-backdrop{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1040;background-color:#000}.modal-backdrop.fade{opacity:0}.modal-backdrop.show{opacity:.5}.modal-header{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:start;-ms-flex-align:start;align-items:flex-start;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between;padding:1rem;border-bottom:1px solid #e9ecef;border-top-left-radius:.3rem;border-top-right-radius:.3rem}
</style>
<style>
    button {
        background: transparent;
        border: none
    }
</style>
<script>
    $(document).ready(function() {
      $('#show_invite_modal').click(function() {
          var room_id = <?php echo $room_id; ?>; // Lấy giá trị room_id từ biến blade $room_id
          var url = "/chat/room/" + room_id + "/members";

          $.ajax({
              url: url,
              type: 'GET',
              success: function(response) {
                  var users = response;

                  // Xóa nội dung cũ trong modal body
                  $('.modal-body').empty();

                  // Lặp qua từng người dùng và render dữ liệu vào modal
                  for (var i = 0; i < users.length; i++) {
                      var user = users[i];
                      var userId = user.id;
                      var userName = user.name;
                      var userEmail = user.email;

                      var userHtml = '<div>';
                      userHtml += '<input type="checkbox" name="user[]" value="' + userId + '">';
                      userHtml += '<label>' + userName + ' - ' + userEmail + '</label>';
                      userHtml += '</div>';

                      $('.modal-body').append(userHtml);
                  }
              },
              error: function(xhr, status, error) {
                  // Xử lý lỗi
                  console.log(error);
              }
          });
      });

      $('#inviteForm').submit(function(e) {
          e.preventDefault();

          var room_id = <?php echo $room_id; ?>; // Lấy giá trị room_id từ biến blade $room_id
          var url = "/chat/room/" + room_id + "/members/add";
          var users = [];

          // Lặp qua tất cả các checkbox được chọn và lấy giá trị user_id
          $('input[name="user[]"]:checked').each(function() {
              users.push($(this).val());
          });

          // Gửi request AJAX để thêm người dùng vào phòng chat
          $.ajax({
              url: url,
              type: 'POST',
              data: { users: users },
              beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
              success: function(response) {
                  // Xử lý thành công
                  console.log(response);
              },
              error: function(xhr, status, error) {
                  // Xử lý lỗi
                  console.log(error);
              }
          });
      });
  });
  </script>
  <!-- CSS -->
<style>
    /* Style cho modal header */
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: none;
    }

    /* Style cho modal title */
    .modal-title {
        margin: 0;
        color: #000;
    }

    /* Style cho modal body */
    .modal-body {
        padding: 20px;
    }

    /* Style cho checkbox và label */
    .modal-body input[type="checkbox"] {
        display: none;
    }

    .modal-body label {
        display: block;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .modal-body label:hover {
        background-color: #f2f2f2;
    }

    /* Thay đổi style của input checkbox khi được chọn */
    .modal-body input[type="checkbox"]:checked + label {
        background-color: #f2f2f2;
    }

    /* Tùy chỉnh style của input checkbox */
    .modal-body input[type="checkbox"] + label:before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 16px;
        margin-right: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        vertical-align: middle;
        background-color: #fff;
    }

    /* Tùy chỉnh style của input checkbox khi được chọn */
    .modal-body input[type="checkbox"]:checked + label:before {
        background-color: #007bff;
    }

    /* Tùy chỉnh style của input checkbox khi được chọn và hover */
    .modal-body input[type="checkbox"]:checked + label:hover:before {
        background-color: #0056b3;
    }
</style>
