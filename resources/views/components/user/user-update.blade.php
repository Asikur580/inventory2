<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update User</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label">User First Name *</label>
                                <input type="text" class="form-control" id="userFirstNameUpdate">

                                <label class="form-label">User Last Name *</label>
                                <input type="text" class="form-control" id="userLastNameUpdate">

                                <label class="form-label">User Email *</label>
                                <input type="text" class="form-control" id="userEmailUpdate">

                                <label class="form-label">User Mobile *</label>
                                <input type="text" class="form-control" id="userMobileUpdate">

                                <label class="form-label">User Password *</label>
                                <input type="password" class="form-control" id="passwordUpdate">

                                <label class="form-label">User Confirm Password *</label>
                                <input type="password" class="form-control" id="confirmPasswordUpdate" onkeyup="validate_password()">

                                <span class="mt-2 d-block" id="u_wrong_pass_alert"></span>

                                <label class="form-label">Role *</label>
                                <select type="text" class="form-control form-select" id="userRoleUpdate">
                                    <option value="">Select Role</option>
                                </select>

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    function validate_password() {

        let updatePass = document.getElementById('passwordUpdate').value;
        let UpdateConfirm_pass = document.getElementById('confirmPasswordUpdate').value;
        if (updatePass != UpdateConfirm_pass) {
            document.getElementById('u_wrong_pass_alert').style.color = 'red';
            document.getElementById('u_wrong_pass_alert').innerHTML = '☒ Use same password';
            document.getElementById('create').disabled = true;
            document.getElementById('create').style.opacity = (0.4);
        } else {
            document.getElementById('u_wrong_pass_alert').style.color = 'green';
            document.getElementById('u_wrong_pass_alert').innerHTML =
                '🗹 Password Matched';
            document.getElementById('create').disabled = false;
            document.getElementById('create').style.opacity = (1);
        }
    }

    async function UpdateFillRoleDropDown() {
        let res = await axios.get("/role")
        res.data.forEach(function(item, i) {
            let option = `<option value="${item['id']}">${item['name']}</option>`
            $("#userRoleUpdate").append(option);
        })
    }

    async function FillUpUpdateForm(id) {
        document.getElementById('updateID').value = id;
        showLoader();

        UpdateFillRoleDropDown();

        let res = await axios.post("/userById", {
            id: id
        })
        hideLoader();
        document.getElementById('userFirstNameUpdate').value = res.data['firstName'];
        document.getElementById('userLastNameUpdate').value = res.data['lastName'];
        document.getElementById('userEmailUpdate').value = res.data['email'];
        document.getElementById('userMobileUpdate').value = res.data['mobile'];
        document.getElementById('userRoleUpdate').value = res.data['roles'][0]['id'];
    }


    async function Update() {

        let customerName = document.getElementById('customerNameUpdate').value;
        let customerEmail = document.getElementById('customerEmailUpdate').value;
        let customerMobile = document.getElementById('customerMobileUpdate').value;
        let updateID = document.getElementById('updateID').value;


        if (customerName.length === 0) {
            errorToast("Customer Name Required !")
        } else if (customerEmail.length === 0) {
            errorToast("Customer Email Required !")
        } else if (customerMobile.length === 0) {
            errorToast("Customer Mobile Required !")
        } else {

            document.getElementById('update-modal-close').click();

            showLoader();

            let res = await axios.post("/update-customer", {
                name: customerName,
                email: customerEmail,
                mobile: customerMobile,
                id: updateID
            })

            hideLoader();

            if (res.status === 200 && res.data === 1) {

                successToast('Request completed');

                document.getElementById("update-form").reset();

                await getList();
            } else {
                errorToast("Request fail !")
            }
        }
    }
</script>