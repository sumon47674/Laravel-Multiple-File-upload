<?php
//Controller
 function onUpload(Request $request){
     $file=$request->file('FileKey');
        $filename=$file->getClientOriginalName();
        $finalName=time().$filename;
        $request->file('FileKey')->storeAs('new/',$finalName,'public');
    }
    //controller end
    ?>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>

     <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    //ajax
    <script>
    
    $('.addBtn').on('click',function () {
        let newTableRow=
                    "<tr>"+
                        "<td><input class=' fileInput form-control' type='file' multiple></td>"+
                        "<td class='fileSize'>File Size </td>"+
                        "<td><button class='btn cancelBtn btn-danger  btn-sm'>Cancel</button></td>"+
                        "<td><button  class='btn upBtn btn-primary btn-sm'>Upload</button></td>"+
                        "<td class='fileUpMB'>Uploaded(MB)</td>"+
                        "<td class='fileUpPercentage'>Uploaded(%)</td>"+
                        "<td class='fileStatus'>Status</td>"+
                    "</tr>";

         $('.fileList').append(newTableRow);

         $('.fileInput').on('change',function () {
               let MyFile= $(this).prop('files');
               let MyFileSize=((MyFile[0].size)/(1024*1024)).toFixed(2);
               $(this).closest('tr').find('.fileSize').html(MyFileSize+ "MB")
         })


         $('.upBtn').on('click',function (event) {
             let MyFile=$(this).closest('tr').find('.fileInput').prop('files')
             let fileUpMB=$(this).closest('tr').find('.fileUpMB');
             let fileUpPercentage=$(this).closest('tr').find('.fileUpPercentage');
             let fileStatus=$(this).closest('tr').find('.fileStatus');
             let Upbtn=$(this);

             let fromData=new FormData();
             console.log(MyFile.length);
             for(let i=0;i<MyFile.length;i++){
                fromData.append('FileKey',MyFile[i]);
             OnFileUpload(fromData,fileUpMB,fileUpPercentage,fileStatus,Upbtn);
             event.preventDefault();
             event.stopImmediatePropagation();
             }
            // fromData.append('FileKey',MyFile[0]);
             // OnFileUpload(fromData,fileUpMB,fileUpPercentage,fileStatus,Upbtn);
             // event.preventDefault();
             // event.stopImmediatePropagation();
         })

         //Remove Row
         $('.cancelBtn').on('click',function () {
                    $(this).parents('tr').remove();
         })

})


function OnFileUpload(fromData,fileUpMB,fileUpPercentage,fileStatus,Upbtn) {
    fileStatus.html("Uploading...");
    Upbtn.prop('disabled',true)

    let url='/fileUpload';
    let config={
        headers:{'content-type':'multipart/form-data'},
        onUploadProgress:function (progressEvent) {
           let UpMB= (progressEvent.loaded/(1024*1024)).toFixed(2) +" MB";
           let UpPer= ((progressEvent.loaded*100)/progressEvent.total).toFixed(2) +" %";
           fileUpMB.html(UpMB)
           fileUpPercentage.html(UpPer)
        }
    }
    axios.post(url,fromData,config)
        .then(function (response) {
            if(response.status==200){
                fileStatus.html('Success')
                Upbtn.prop('disabled',false)
            }
            else{
                fileStatus.html('Fail')
                Upbtn.prop('disabled',false)
            }

        }).catch(function (error) {
        fileStatus.html('Main Issue')
        Upbtn.prop('disabled',false)
    })

}
    </script>
    
    
    <!---View of the template--->
        <div class="container-fluid mt-5">
    <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Laravel Axios Multipul File Uploader</h4>
                    </div>
                    <div class="card-body">
                            <button class="btn addBtn btn-primary my-3 btn-sm">Add File</button>
                            <table class="table table-striped">
                                <thead>
                                        <tr>
                                            <th>File</th>
                                            <th>File Size</th>
                                            <th>Cancel</th>
                                            <th>Upload</th>
                                            <th>Uploaded(MB)</th>
                                            <th>Uploaded(%)</th>
                                            <td>Status</td>
                                        </tr>
                                </thead>
                                <tbody class="fileList">

                                </tbody>
                            </table>

                    </div>
                </div>

            </div>
    </div>
</div>
    
