<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Role</h5>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Role Name *</label>
                                <input type="text" class="form-control" id="name">
                                <label class="form-label">Slug *</label>
                                <input type="text" class="form-control" id="slug">                                
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="Save()" id="save-btn" class="btn bg-gradient-success" >Save</button>
                </div>
            </div>
    </div>
</div>


<script>

let roleItem=[];

    async function Save() {

        let name = document.getElementById('name').value;
        let slug = document.getElementById('slug').value;        

        if (name.length === 0) {
            errorToast("Role Name Required !")
        }
        else if(slug.length===0){
            errorToast("Slug Required !")
        }
        else {

            document.getElementById('modal-close').click();

            let data = {
                "name":name,
                "slug":slug
            }

            showLoader();
            let res = await axios.post("/role",data)
            hideLoader();

            if(res.status===200){

                successToast('Request completed');

                document.getElementById("save-form").reset();

                await getList();
            }
            else{
                errorToast("Request fail !")
            }
        }
    }

</script>
