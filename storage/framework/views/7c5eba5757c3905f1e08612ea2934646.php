
<?php $__env->startSection('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/inputKeyword.css')); ?>">

<script src="https://cdn.tiny.cloud/1/bavaaeovtcjzdwp4vi1piljl226ox5dyf0r3js5wjieu2ysm/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<style>
.btn-to-right {
    float: right;
}
.dropzone {
    min-height:250px;
}

</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Container-fluid start -->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-header-left">
                        <h3>Sửa bài viết
                           <small>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo e(route('home')); ?>">
                                        <i class="fa fa-home"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item ">- Quản lý bài viết</li>
                            </ol>    
                        </small> 
                        </h3>
                    </div>
                </div>
                <div class="col-sm-6">

                    <a href="<?php echo e(route('news.index')); ?>" class="btn btn-outline-warning btn-to-right">Quay lại</a>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid end -->

    <!-- Container-fluid start -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card"> 
                    <div class="card-header pb-0">
                        <h5>Cập nhật thông tin bài viết</h5>
                    </div>
                     <div class="card-body admin-form">
                        <div id="form_create"  class="row gx-3">
                          
                            <div class="col-lg-8">
                                <div class="form-group col-md-12 col-sm-12">
                                    <label>Tên bài viết <span class="font-danger">*</span></label>
                                    <input type="text" value="<?php echo e($data->title); ?>"  id="name" class="form-control"  required>
                                   
                                </div>
                                <div class="form-group col-md-12 col-sm-12">
                                    <label>Loại bài viết <span class="font-danger">*</span></label>
                                    <select class="dropdown col-12 p-2" name="category_id" id="category_id">
                                        <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="">---</option>
                                        <option value="<?php echo e($item->id); ?>" <?php if($item->id == $data->category_id): ?> selected <?php endif; ?>><?php echo e($item->name); ?></option>       
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12">
                                    <label>Tóm tắt <span class="font-danger">*</span></label>
                                    <textarea type="text"  id="short_content" class="form-control"><?php echo e($data->short_content); ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="dropzone-admin mb-0">
                                    <label>Ảnh đại diện</label>
                                    <form class="dropzone" id="UploadThumnailNews" action="<?php echo e(route('news.uploadThumnail')); ?>">
                                        <img src="<?php echo e(asset('images/thumbnail_news/'.$data->thumbnail)); ?>" id="bg-img" class="bg-img" alt="">
                                        <?php echo csrf_field(); ?>
                                        <div class="dz-message needsclick"><i class="fas fa-cloud-upload-alt"></i>
                                        <h6>Thả ảnh hoặc nhấn vào đây để upload.</h6>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="content">Nội dung</label>
                                <textarea name="content" id="content" cols="30" class="form-control" ><?php echo e($data->content); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label for="content">Từ khóa</label>
                                <div class="input-area" id="divKeywords">
                                    <input type="text" id="txtInput" class="form-control" placeholder="Enter keyword..." />
                                  </div>
                            </div>
                            <div class="form-btn">
                                <button id="submit" class="btn btn-pill btn-gradient color-4">Lưu</button>
                                <a href="<?php echo e(route('news.index')); ?>" class="btn btn-pill btn-dashed color-4">Quay lại</a>
                            </div>
                        </div>
                       
                       
                     </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid end -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script>
    function makeToast(title,color = "green") {
        Toastify({
    text: title,
    className: "info",
    style: {
        background: color,
        }
}).showToast();
    }

</script>
<script>
    var allKeywords = [];
    <?php $__currentLoopData = json_decode($data->key_words, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        allKeywords.push('<?php echo e($item); ?>')
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    allKeywords.forEach(function(e) {
        $('#divKeywords > input[type=text]').before($('<p class="keyword">' + e + '<a class="delete" onclick="deleteWord(this)"><i class="fa fa-times" aria-hidden="true"></i></a></p>'));
    })
</script>
<?php echo $__env->make('js.news.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- datepicker js-->
    <script src="<?php echo e(asset('assets/js/date-picker.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/inputKeyword.js')); ?>"></script>
    <script>
        tinymce.init({
          selector: '#content',
          plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
      </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\tai_lieu_hoc_tap\nhap_mon_cnpm\Web_QL_PhongTro_L10\resources\views/dashboard/news/edit.blade.php ENDPATH**/ ?>