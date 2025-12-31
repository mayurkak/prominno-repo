@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Add New Product</div>

            <div class="card-body">
                <form action="{{ url('seller/products') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>Product Name<span class="text-danger">*</span></label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="product_description" class="form-control"></textarea>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <h5>Brands (Multiple)</h5>
                        <button type="button" id="add-brand" class="btn btn-success btn-sm">+ Add Brand</button>
                    </div>

                    <div id="brand-container">
                        <div class="brand-card card shadow-sm mt-3">
                            <div class="card-header bg-white d-flex justify-content-between">
                                <strong class="brand-title">Brand #1</strong>
                                <button type="button" class="btn btn-danger btn-sm remove-brand d-none">Remove</button>
                            </div>

                            <div class="card-body">

                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>Brand Name<span class="text-danger">*</span></label>
                                        <input type="text" name="brands[0][name]" class="form-control"
                                            placeholder="Enter brand name" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label>Price<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs.</span>
                                            <input type="number" name="brands[0][price]" class="form-control"
                                                value="0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Detail</label>
                                    <textarea name="brands[0][detail]" class="form-control" rows="2" placeholder="Enter brand details"></textarea>
                                </div>

                                <label>Image</label>

                                <div class="border rounded p-3 text-center image-box" style="border:2px dashed #ccc">
                                    <input type="file" name="brands[0][image]" class="form-control brand-image-input">

                                    <div class="preview-area mt-2 d-none">
                                        <img src="" class="img-thumbnail" style="max-height:120px">
                                        <div>
                                            <button type="button" class="btn btn-link text-danger p-0 remove-image">Remove
                                                Image</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <button class="btn btn-success mt-3 float-end" type="submit">Save Product</button>

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let brandIndex = 1;

        document.getElementById('add-brand').addEventListener('click', () => {

            let container = document.getElementById('brand-container');

            let clone = container.firstElementChild.cloneNode(true);

            clone.querySelectorAll('input, textarea').forEach(el => {
                el.value = '';
            });

            clone.innerHTML = clone.innerHTML.replaceAll(/\brands\[\d+\]/g, `brands[${brandIndex}]`);

            clone.querySelector('.remove-brand').classList.remove('d-none');

            clone.querySelector('.preview-area').classList.add('d-none');

            container.appendChild(clone);

            updateTitles();
            brandIndex++;
        });

        document.addEventListener('click', e => {
            if (e.target.classList.contains('remove-brand')) {
                e.target.closest('.brand-card').remove();
                updateTitles();
            }
        });

        function updateTitles() {
            document.querySelectorAll('.brand-card').forEach((card, index) => {
                card.querySelector('.brand-title').innerText = `Brand #${index + 1}`;
            });
        }

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('brand-image-input')) {
                let fileInput = e.target;
                let preview = fileInput.closest('.image-box').querySelector('.preview-area');
                let img = preview.querySelector('img');

                if (fileInput.files && fileInput.files[0]) {
                    img.src = URL.createObjectURL(fileInput.files[0]);
                    preview.classList.remove('d-none');
                }
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-image')) {
                let box = e.target.closest('.image-box');
                box.querySelector('.brand-image-input').value = '';
                box.querySelector('.preview-area').classList.add('d-none');
            }
        });

        $(document).ready(function() {

            $("form").on("submit", function(e) {
                e.preventDefault();

                var form = this;
                var url = $(form).attr("action");

                var formData = new FormData(form);

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    headers: {
                        'Accept': 'application/json'
                    },

                    beforeSend: function() {
                        $(".error-text").remove();
                    },

                    success: function(res) {
                        if (res.status === true) {
                            alert(res.message);
                            form.reset();
                            window.location.href = "/products";
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {
                                $(`[name="${key}"]`)
                                    .after(
                                        `<small class="text-danger error-text">${value[0]}</small>`
                                        );
                            });

                            alert("Validation failed");
                        } else {
                            alert("Server error, please try again.");
                            console.log(xhr.responseText);
                        }
                    }
                });

            });

        });
    </script>
@endsection
