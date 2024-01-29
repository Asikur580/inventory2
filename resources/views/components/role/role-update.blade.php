<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Role Name *</label>
                                <input type="text" class="form-control" id="updateName">

                                <label class="form-label mt-3">Slug *</label>
                                <input type="text" class="form-control" id="updateSlug">                                

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>


<script>



    async function FillUpUpdateForm(id){
        document.getElementById('updateID').value=id;
        showLoader();
        let res=await axios.post("/roleById",{id:id})
        hideLoader();
        document.getElementById('updateName').value=res.data['name'];
        document.getElementById('updateSlug').value=res.data['slug'];        
    }


    async function Update() {

        let name = document.getElementById('updateName').value;
        let slug = document.getElementById('updateSlug').value;        
        let updateID = document.getElementById('updateID').value;


        if (name.length === 0) {
            errorToast("Role Name Required !")
        }
        else if(slug.length===0){
            errorToast("Slug Required !")
        }
        else {

            document.getElementById('update-modal-close').click();

            showLoader();

            let res = await axios.post("/roleUpdate",{name:name,slug:slug,id:updateID})

            hideLoader();

            if(res.status===200 && res.data===1){

                successToast('Request completed');

                document.getElementById("update-form").reset();

                await getList();
            }
            else{
                errorToast("Request fail !")
            }
        }
    }

</script>
