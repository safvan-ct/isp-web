function showLoader() {
    document.getElementById("backdrop-loader").style.display = "flex";
}

function hideLoader() {
    document.getElementById("backdrop-loader").style.display = "none";
}

toastr.options = {
    // closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right", // Top-right, bottom-left, etc.
    timeOut: 5000, // 5 seconds
};

// Data Create /Update
function storeData(data, url, method, table = "dataTable") {
    toastr.clear();
    showLoader();

    $.ajax({
        url: url,
        type: method,
        data: data,
        success: function (res, status) {
            if (status == "success") {
                toastr.success(res.message);
                $(".createUpdate").modal("hide");
                $(`#${table}`).DataTable().ajax.reload(null, false);
            } else {
                toastr.error(res.message || "An unexpected error occurred");
            }
        },
        error: function (xhr, status, error) {
            toastr.error(
                xhr.responseJSON?.error || // custom `error`
                    xhr.responseJSON?.message || // general `message`
                    (xhr.responseJSON?.errors
                        ? Object.values(xhr.responseJSON.errors)
                              .flat()
                              .join("<br>")
                        : null) || // validation errors
                    "Server error. Please try again." // fallback
            );

            // if (xhr.responseJSON?.errors) {
            //     Object.values(xhr.responseJSON.errors).forEach((messages) => {
            //         messages.forEach((msg) => allMessages.push(msg));
            //     });
            // }
            // errorMessage = allMessages.join("<br>");

            // toastr.error(errorMessage);
        },
        complete: function () {
            hideLoader();
        },
    });
}

// Data Delete
async function deleteItem(url, token, table = "dataTable") {
    if (!confirm("Are you sure you want to delete this item?")) return;

    showLoader();
    toastr.clear();

    try {
        const response = await fetch(url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": token,
                Accept: "application/json",
            },
        });

        const res = await response.json();
        hideLoader();

        if (!response.ok) {
            throw new Error(res.message || "An unexpected error occurred");
        }

        toastr.success(res.message);
        $(`#${table}`).DataTable().ajax.reload(null, false);
    } catch (error) {
        hideLoader();
        toastr.error(error.message || "Oops! Something went wrong.");
    }
}

// Status Change
function updateStatus(url, token, table = "dataTable") {
    toastr.clear();
    showLoader();

    $.ajax({
        url: url,
        type: "PATCH",
        data: {
            _token: token,
        },
        success: function (res, status) {
            if (status == "success") {
                $(`#${table}`).DataTable().ajax.reload(null, false);
                toastr.success(res.message);
            } else {
                toastr.error(res.message || "An unexpected error occurred");
            }
        },
        error: function (xhr) {
            toastr.error(
                xhr.responseJSON?.error || // custom `error`
                    xhr.responseJSON?.message || // general `message`
                    (xhr.responseJSON?.errors
                        ? Object.values(xhr.responseJSON.errors)
                              .flat()
                              .join("<br>")
                        : null) || // validation errors
                    "Server error. Please try again." // fallback
            );
        },
        complete: function () {
            hideLoader();
        },
    });
}

// Sort Data
function makeSortable() {
    $("#sortableBody")
        .sortable({
            helper: fixHelper,
            cursor: "move",
        })
        .disableSelection();
}

const fixHelper = (e, ui) => {
    ui.children().each(function () {
        $(this).width($(this).width());
    });
    return ui;
};

function sortData(url, token) {
    toastr.clear();
    showLoader();

    const order = [];
    $("#sortableBody tr").each(function (index, element) {
        const id = $(element).attr("id");
        if (id) {
            order.push({
                id: id.replace("row_", ""),
                position: index + 1,
            });
        }
    });

    $.ajax({
        url: url,
        method: "POST",
        data: {
            _token: token,
            order: order,
        },
        success: function (res) {
            toastr.success(res.message);
            $("#dataTable").DataTable().ajax.reload(null, false);
        },
        error: function () {
            toastr.error("Failed to update order.");
        },
        complete: function () {
            hideLoader();
        },
    });
}
