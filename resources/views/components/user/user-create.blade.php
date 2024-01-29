<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">User First Name *</label>
                                <input type="text" class="form-control" id="userFirstName">
                                <label class="form-label">User Last Name *</label>
                                <input type="text" class="form-control" id="userLastName">
                                <label class="form-label">User Email *</label>
                                <input type="text" class="form-control" id="userEmail">
                                <label class="form-label">User Mobile *</label>
                                <input type="text" class="form-control" id="userMobile">
                                <label class="form-label">User Password *</label>
                                <input type="password" class="form-control" id="password">
                                <label class="form-label">User Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPassword" onkeyup="validate_password()">
                                <span class="mt-2 d-block" id="wrong_pass_alert"></span>
                                <label class="form-label">Role *</label>
                                <select type="text" class="form-control form-select" id="userRole">
                                    <option value="">Select Role</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="Save()" id="save-btn" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>


<script>
    FillRoleDropDown();

    async function FillRoleDropDown() {
        let res = await axios.get("/role")
        res.data.forEach(function(item, i) {
            let option = `<option value="${item['id']}">${item['name']}</option>`
            $("#userRole").append(option);
        })
    }

    function validate_password() {

        let pass = document.getElementById('password').value;
        let confirm_pass = document.getElementById('confirmPassword').value;
        if (pass != confirm_pass) {
            document.getElementById('wrong_pass_alert').style.color = 'red';
            document.getElementById('wrong_pass_alert').innerHTML = '☒ Use same password';
            document.getElementById('create').disabled = true;
            document.getElementById('create').style.opacity = (0.4);
        } else {
            document.getElementById('wrong_pass_alert').style.color = 'green';
            document.getElementById('wrong_pass_alert').innerHTML =
                '🗹 Password Matched';
            document.getElementById('create').disabled = false;
            document.getElementById('create').style.opacity = (1);
        }
    }

    let roleItem = [];

    async function Save() {

        let userFirstName = document.getElementById('userFirstName').value;
        let userLastName = document.getElementById('userLastName').value;
        let userEmail = document.getElementById('userEmail').value;
        let userMobile = document.getElementById('userMobile').value;
        let password = document.getElementById('password').value;
        let userRole = document.getElementById('userRole').value;

        if (userFirstName.length === 0) {
            errorToast("User First Name Required !")
        } else if (userLastName.length === 0) {
            errorToast("User Last Name Required !")
        } else if (userEmail.length === 0) {
            errorToast("User Email Required !")
        } else if (userMobile.length === 0) {
            errorToast("User Mobile Required !")
        } else if (password === 0) {
            errorToast("User Password Required !")
        } else if (userRole === 0) {
            errorToast("User Role Required !")
        } else {

            document.getElementById('modal-close').click();

            let data = {
                "firstName": userFirstName,
                "lastName": userLastName,
                "email": userEmail,
                "mobile": userMobile,
                "password": password,
                "role": userRole

            }

            showLoader();
            let res = await axios.post("/user", data)
            hideLoader();

            if (res.status === 200) {

                successToast('Request completed');

                document.getElementById("save-form").reset();

                await getList();
            } else {
                errorToast("Request fail !")
            }
        }
    }
</script>