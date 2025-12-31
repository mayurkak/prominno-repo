@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    <h1 class="mt-4">
        {{ isset($seller) ? 'Edit Seller' : 'Create New Seller' }}
    </h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-header">
            <i class="fas fa-user-plus me-1"></i>
            {{ isset($seller) ? 'Update Seller Details' : 'Seller Details' }}
        </div>

        <div class="card-body">

            <form id="sellerForm">
                @csrf

                <!-- detect edit mode -->
                <input type="hidden" id="seller_id" value="{{ $seller->id ?? '' }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ $seller->name ?? '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $seller->email ?? '' }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_no" class="form-control"
                               value="{{ $seller->mobile_no ?? '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Password</label>

                        <input type="password" name="password" class="form-control"
                               {{ isset($seller) ? '' : 'required' }}>

                        @if(isset($seller))
                            <small class="text-muted">Leave blank to keep same password</small>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control"
                               value="{{ $seller->country ?? '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>State</label>
                        <input type="text" name="state" class="form-control"
                               value="{{ $seller->state ?? '' }}" required>
                    </div>
                </div>

                <hr>

                <h5>Skills</h5>

                <div id="skillWrapper">

                    @if(isset($seller) && is_array($seller->skills))
                        @foreach($seller->skills as $skill)
                            <div class="row skillRow mb-2">
                                <div class="col-md-10">
                                    <input type="text" name="skills[]" class="form-control" value="{{ $skill }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger removeSkill">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row skillRow mb-2">
                            <div class="col-md-10">
                                <input type="text" name="skills[]" class="form-control" placeholder="Enter Skill">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger removeSkill">Remove</button>
                            </div>
                        </div>
                    @endif

                </div>

                <button type="button" id="addSkill" class="btn btn-success mt-2">
                    Add Skill +
                </button>

                <div class="col-md-6 mb-3 mt-3">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Select status</option>
                        <option value="active" {{ (isset($seller) && $seller->status=='active')?'selected':'' }}>Active</option>
                        <option value="inactive" {{ (isset($seller) && $seller->status=='inactive')?'selected':'' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                    {{ isset($seller) ? 'Update Seller' : 'Save Seller' }}
                </button>

            </form>

        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    // Add Skill
    $('#addSkill').click(function () {
        $('#skillWrapper').append(`
            <div class="row skillRow mb-2">
                <div class="col-md-10">
                    <input type="text" name="skills[]" class="form-control" placeholder="Enter Skill">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger removeSkill">Remove</button>
                </div>
            </div>
        `);
    });

    // Remove Skill
    $(document).on('click', '.removeSkill', function () {
        $(this).closest('.skillRow').remove();
    });

    // =========================
    // AJAX store + update
    // =========================
    $('#sellerForm').submit(function (e) {
        e.preventDefault();

        let seller_id = $('#seller_id').val();
        let formData = new FormData(this);

        let url = "";
        let method = "POST";


            url = "{{ route('admin.sellers.store') }}";

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function () {
                $('#submitBtn').prop('disabled', true).text('Saving...');
            },

            success: function (res) {
                alert(res.message || "Seller saved successfully");
                window.location.href = "{{ route('admin.sellers') }}";
            },

            error: function (xhr) {
                $('#submitBtn').prop('disabled', false).text('Save Seller');

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let msg = "";
                    $.each(xhr.responseJSON.errors, function (key, val) {
                        msg += val + "\n";
                    });
                    alert(msg);
                } else {
                    alert("Something went wrong");
                }
            }
        });
    });

});
</script>
