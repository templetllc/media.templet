(function () {
    "use strict";

    $(document).ready(function () {
        // Ajax setup headers
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Csrf token
            },
        });

        const types = ["images", "icons"];

        if (!types.includes(selectedImages.type)) {
            selectedImages.cleanAll();
        }

        addSelections();

        countCheck();

        // Check Image
        $("body").on("change", "[data-toggle=checkImage]", function (e) {
            e.preventDefault();
            var id = $(this).val();
            var composedId = `img_${id}`;

            if ($(this).is(":checked")) {
                $(this).parents(".img-item").addClass("imgCheck");
                $(this)
                    .parents(".img-item")
                    .find("img#img_" + id)
                    .addClass("active");
                if ($(this).parents(".icon-item")) {
                    $(this).parents(".icon-item").addClass("active");
                }
                selectedImages.add(composedId);
            } else {
                $(this).parents(".img-item").removeClass("imgCheck");
                $(this)
                    .parents(".img-item")
                    .find("img#img_" + id)
                    .removeClass("active");
                if ($(this).parents(".icon-item")) {
                    $(this).parents(".icon-item").removeClass("active");
                }
                selectedImages.remove(composedId);
            }

            countCheck();
        });

        // Check All Image
        $("body").on("click", "[data-toggle=selectAll]", function (e) {
            e.preventDefault();
            const imgItems = $(".gallery-approval .img-item");
            const iconItems = $(".gallery-approval .icon-item");

            $(".gallery-approval input[type=checkbox]").prop("checked", true);
            imgItems.addClass("imgCheck");
            imgItems.find("img").addClass("active");
            iconItems.addClass("active");

            const imgIds = imgItems
                .find("img")
                .map(function () {
                    return $(this).attr("id");
                })
                .toArray();

            const iconIds = iconItems
                .find("img")
                .map(function () {
                    return $(this).attr("id");
                })
                .toArray();

            selectedImages.add([...imgIds, ...iconIds]);

            countCheck();
        });

        // Uncheck All Image
        $("body").on("click", "[data-toggle=deselectAll]", function (e) {
            e.preventDefault();
            const imgItems = $(".gallery-approval .img-item");
            const iconItems = $(".gallery-approval .icon-item");

            $(".gallery-approval input[type=checkbox]").prop("checked", false);
            imgItems.removeClass("imgCheck");
            imgItems.find("img").removeClass("active");
            $(".gallery-approval").find(".img-item").removeClass("active");

            iconItems.removeClass("active");

            const imgIds = imgItems
                .find("img")
                .map(function () {
                    return $(this).attr("id");
                })
                .toArray();

            const iconIds = iconItems
                .find("img")
                .map(function () {
                    return $(this).attr("id");
                })
                .toArray();

            selectedImages.remove([...imgIds, ...iconIds]);

            countCheck();
        });

        // Approvals Images
        $("body").on("click", "[data-toggle=approvalImage]", function (e) {
            changeImageListStatus(e, "Approve");
        });

        // Unapprovals Images
        $("body").on("click", "[data-toggle=unapprovalImage]", function (e) {
            changeImageListStatus(e, "Unapprove");
        });

        // Approval Image Details
        $("body").on("click", "[data-toggle=approvalDetail]", function (e) {
            e.preventDefault();

            var id = $(this).attr("id");
            var query = "/approvals/approve/" + id;
            $.ajax({
                url: query,
                type: "get",
                async: false,
                success: function (response) {
                    console.log(response);
                },
                complete: function (response) {
                    swal("Success!", "Images successfully approved", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: "btn btn-success",
                            },
                        },
                    }).then(function () {
                        reloadDetailPageAfterChange("unapproved", "approved");
                    });
                },
            });
        });

        // Approval Image Details
        $("body").on("click", "[data-toggle=unapprovalDetail]", function (e) {
            e.preventDefault();

            var id = $(this).attr("id");
            var query = "/approvals/unapprove/" + id;
            $.ajax({
                url: query,
                type: "get",
                async: false,
                success: function (response) {
                    console.log(response);
                },
                complete: function (response) {
                    swal("Success!", "Images successfully unapproved", {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: "btn btn-success",
                            },
                        },
                    }).then(function () {
                        reloadDetailPageAfterChange("approved", "unapproved");
                    });
                },
            });
        });
    });

    var countCheck = function () {
        const count = selectedImages.count();

        $("#actionImages").find("#selected").html(count);
        $("#actionImages").find(".btn").attr('disabled', count <= 0);
    };

    var reloadDetailPageAfterChange = function (prevStatus, nextStatus) {
        selectedImages.cleanAll();
        if (window.location.href.includes(prevStatus)) {
            window.location.href = window.location.href.replace(
                prevStatus,
                nextStatus
            );
        } else {
            location.reload();
        }
    };

    var changeImageListStatus = function (e, status) {
        e.preventDefault();
        const lower = status.toLowerCase();
        const past = `${lower}d`;
        const ids = $(".gallery-approval")
            .find("#checkImage:checked")
            .map(function () {
                return $(this).val();
            })
            .toArray();
        swal({
            icon: "info",
            title: "Are you sure?",
            text: `${ids.length} items will be ${past}, do you wish to proceed?`,
            buttons: {
                confirm: {
                    text: status,
                    className: "btn btn-primary will-load",
                    closeModal: false,
                },
                cancel: {
                    visible: true,
                    className: "btn btn-secondary",
                },
            },
        }).then((willApprove) => {
            if (willApprove) {
                document
                    .querySelector(".will-load")
                    .classList.add("btn-primary-loading");
                $.ajax({
                    url: `/approvals/${lower}/multiple`,
                    type: "post",
                    async: false,
                    data: { ids },
                    success: () => {
                        document
                            .querySelector(".will-load")
                            .classList.remove("btn-primary-loading");
                        swal("Success!", `Images successfully ${past}`, {
                            icon: "success",
                            buttons: {
                                confirm: {
                                    className: "btn btn-success",
                                },
                            },
                        }).then(() => {
                            selectedImages.cleanAll();
                            location.reload();
                        });
                    },
                });
            } else {
                swal.close();
            }
        });
    };

    var selectedImages = {
        key: "__media_templet__selected_images",
        type: window.location.pathname.substring(1).split("/")[0],
        tab: window.location.pathname.substring(1).split("/")[2],
        page: new URL(window.location.href).searchParams.get("page") ?? "1",
        getRoot: function () {
            const items = localStorage.getItem(this.key);

            if (items) {
                const parsed = JSON.parse(items);

                if (!parsed[this.type]) {
                    parsed[this.type] = {};
                }

                if (!parsed[this.type][this.tab]) {
                    parsed[this.type][this.tab] = {};
                }

                if (!parsed[this.type][this.tab][this.page]) {
                    parsed[this.type][this.tab][this.page] = [];
                }

                return parsed;
            }

            return {
                [this.type]: {
                    [this.tab]: {
                        [this.page]: [],
                    },
                },
            };
        },
        get: function () {
            return this.getRoot()[this.type][this.tab][this.page];
        },
        save: function (items) {
            const root = this.getRoot();

            if (!root[this.type]) {
                root[this.type] = {};
            }

            if (!root[this.type][this.tab]) {
                root[this.type][this.tab] = {};
            }

            root[this.type][this.tab][this.page] = items;

            localStorage.setItem(this.key, JSON.stringify(root));
        },
        count: function () {
            return this.get().length;
        },
        add: function (value) {
            if (Array.isArray(value)) {
                this.save([...this.get(), ...value]);
            } else {
                this.save([...this.get(), value]);
            }
        },
        clean: function () {
            this.save([]);
        },
        remove: function (value) {
            if (Array.isArray(value)) {
                this.save(this.get().filter((item) => !value.includes(item)));
            } else {
                this.save(this.get().filter((item) => item !== value));
            }
        },
        cleanAll: function () {
            localStorage.removeItem(this.key);
        },
    };

    var addSelections = function () {
        const container = ".gallery-approval";
        const parser = (type) => (item) => ({ node: item, type });

        const images = [
            ...document.querySelectorAll(`${container} .img-item`),
        ].map(parser("images"));

        const icons = [
            ...document.querySelectorAll(`${container} .icon-item`),
        ].map(parser("icon"));

        [...images, ...icons].forEach((item) => {
            const img = item.node.querySelector("img");
            const checkbox = item.node.querySelector("#checkImage");
            const id = img.getAttribute("id");

            if (selectedImages.get().includes(id)) {
                if (item.type === "image") {
                    item.node.classList.add("imgCheck");
                    img.classList.add("active");
                } else {
                    item.node.classList.add("active");
                }
                checkbox.setAttribute("checked", true);
            }
        });
    };
})(jQuery);
