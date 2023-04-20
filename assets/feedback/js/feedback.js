class FeedbackModule {
    constructor(config) {
        this.path;
        this.offsetTop;
        this.feedbackElements;
        this.disableOverlay;
        this.leftPositionKey = 'leftposition';
        this.topPositionKey = 'topposition';
        this.feedbackIndexKey = 'feedbackindex';

        if (typeof config === 'string') {
            this.jsonFile = config;
        } else {
            const {
                jsonFile,
                offsetTop,
                feedbackElements,
                path,
                disableOverlay
            } = config;

            this.jsonFile = jsonFile;
            this.offsetTop = offsetTop.length == 0 ? 0 : offsetTop;
            this.feedbackElements = feedbackElements;
            this.path = path;
            this.disableOverlay = disableOverlay;
        }
    }

    loadFeedback() {
        alert(this.jsonFile);
    }

    createSidebarNav() {
        const sidebarNav = document.createElement("div");
        sidebarNav.setAttribute("class", "sidebar-nav");

        return sidebarNav;
    }

    createCommentsList() {
        const commentsList = document.createElement("div");
        commentsList.setAttribute("class", "comment-list-heading feedback-comment-list");

        return commentsList;
    }

    createScrollContainer(commentsList) {
        const scrollContainer = document.createElement("div");
        scrollContainer.setAttribute("class", "scroll-container");
        scrollContainer.setAttribute("id", "style-3");
        scrollContainer.setAttribute("data-spy", "scroll");
        scrollContainer.setAttribute("data-offset", "0");
        scrollContainer.appendChild(commentsList);

        return scrollContainer;
    }

    createSidebarWrapper(sidebarNav, scrollContainer) {
        const sidebarWrapper = document.createElement("div");
        sidebarWrapper.setAttribute("id", "sidebar-wrapper");

        sidebarWrapper.appendChild(sidebarNav);
        sidebarWrapper.appendChild(scrollContainer);

        return sidebarWrapper;
    }

    createFeedbackWrapper(self, sidebarWrapper) {
        const feedbackWrapper = document.createElement("div");
        feedbackWrapper.setAttribute("id", "feedback-wrapper");
        feedbackWrapper.setAttribute("class", "feedbackPanel toggled");
        feedbackWrapper.setAttribute("data-json", self.jsonFile);

        feedbackWrapper.appendChild(sidebarWrapper);

        return feedbackWrapper;
    }

    createHeader() {
        return `
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div><h3>Feedback</h3></div>
                <button class="close feedback-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11.997" viewBox="0 0 12 11.997">
                        <path
                            id="Icon_ionic-ios-close"
                            data-name="Icon ionic-ios-close"
                            d="M18.707,17.287,22.993,13a1,1,0,0,0-1.42-1.42l-4.286,4.286L13,11.581A1,1,0,1,0,11.581,13l4.286,4.286-4.286,4.286A1,1,0,0,0,13,22.993l4.286-4.286,4.286,4.286a1,1,0,0,0,1.42-1.42Z"
                            transform="translate(-11.285 -11.289)"
                            fill="#58595b"
                        />
                    </svg>
                </button>
            </div>
            <hr/>
        `;
    }

    createToolbar(self) {
        const root = document.createElement('div');
        root.classList.add('d-flex', 'justify-content-between', 'mb-3');

        const button = document.createElement('button');
        button.classList.add('btn-placepin');
        button.addEventListener('click', self.activeFeedbackButtonClickHandler());
        button.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16.661" height="16.661" viewBox="0 0 16.661 16.661">
                        <path
                            id="Path_2453"
                            data-name="Path 2453"
                            d="M16.458,5.113,11.549.2a.694.694,0,0,0-.982,0L8.6,2.167a.694.694,0,0,0,0,.982l.491.491-3.6,3.6a4.166,4.166,0,0,0-4.74.814.694.694,0,0,0,0,.982L3.7,11.986l-.021.019L.2,15.477a.694.694,0,0,0,.982.982l3.471-3.471.019-.021,2.946,2.946a.694.694,0,0,0,.982,0,4.166,4.166,0,0,0,.814-4.741l3.6-3.6.491.491a.694.694,0,0,0,.982,0l1.963-1.963A.694.694,0,0,0,16.458,5.113ZM8.051,14.378,2.284,8.611a2.778,2.778,0,0,1,3.374.429L7.622,11A2.777,2.777,0,0,1,8.051,14.378ZM8.6,10.022,6.64,8.058l3.436-3.436L12.04,6.585ZM14,6.585l-.49-.49h0L10.567,3.149h0l-.49-.49.982-.982L14.985,5.6Z"
                            transform="translate(0 -0.001)"
                            fill=""
                        />
                    </svg>
                </div>
                <div class="ml-2">
                    <h3 class="text-green active-pin">Place a pin</h3>
                </div>
            </div>
        `;

        const resolverRoot = document.createElement('div');
        resolverRoot.classList.add('d-flex', 'justify-content-between', 'align-items-center');
        resolverRoot.innerHTML = `
            <div class="mr-2">
                <h3 class="text-resolved">Resolved</h3>
            </div>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input resolved-status" id="resolved-status">
                <label class="custom-control-label" for="resolved-status"></label>
            </div>
        `

        root.appendChild(button)
        root.appendChild(resolverRoot)

        return root;
    }

    createOverlay() {
        const overlay = document.createElement('div');
		overlay.setAttribute("class", "feedback-overlay");
		overlay.setAttribute("style", "height: "+$(document).height()+"px; width: 100%;");

        return overlay;
    }

    createTextarea() {
        return `
            <div class="form-group">
                <textarea class="form-control" id="textarea-comment" rows="2" placeholder="Make a comment" disabled></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <div><a class="btn btn-secondary btn-cancel_feedback"><span>Cancel</span></a></div>
                <div><a class="btn btn-primary btn-send_feedback"><span>Send</span></a></div>
            </div>
            <hr class="my-3"/>
        `;
    }

    insertMarksAndCommentsFromRaw(self, rawMarks, rawComments) {
        const marks = document.createElement('div');
        marks.innerHTML = rawMarks;

        marks.querySelectorAll('div').forEach(mark => {
            if (mark.getAttribute('parentid')) {
                const wrapperElement = document.getElementById(mark.getAttribute('parentid'));

                if (wrapperElement) {
                    mark.style.left = `calc(${mark.getAttribute(self.leftPositionKey)}px - 15px)`;
                    mark.style.top =  `calc(${mark.getAttribute(self.topPositionKey)}px - 15px)`;
                    wrapperElement.appendChild(mark)
                } else {
                    $("body").append(mark);
                }
            } else {
                $("body").append(mark);
            }
        })

        $(".feedback-comment-list").html(rawComments);
    }

    initialFeedbackLoad(self, parameters) {
        const width = $(window).width();
        const height = $(document).height();

        if (parameters) {
            self.offsetTop = parameters.offsetTop.length == 0 ? 0 : parameters.offsetTop;
        }

        if (typeof self.path === "undefined") {
            const { pathname, origin } = window.location;
            self.path = `${origin}${pathname.substr(0, pathname.lastIndexOf("admin"))}admin/assets`;
        }

        $.ajax({
            type: "GET",
            url: `${self.path}/feedback/include/getFeedbackList.php`,
            data: { feedback: self.jsonFile, width, height },
            contentType: "application/json",
            async: true,
            success: function (response) {
                if (response.length > 5) {
                    const { marks, comments, pending } = jQuery.parseJSON(response)[0];

                    self.insertMarksAndCommentsFromRaw(self, marks, comments);

                    $(".sidebar-feedback").hide();
                    $('.sidebar-feedback[visible="visible"]').show();
                    $(".feedback-pin").hide();

                    $(document)
                        .find('[data-action="feedback"]')
                        .addClass("has-feedback");

                    if (pending > 0) {
                        $(document)
                            .find('[data-action="feedback"]')
                            .append(`<span class="badge badge-warning">${pending}</span>`);
                    } else {
                        $(document)
                            .find('[data-action="feedback"]')
                            .addClass("no-pending");
                    }
                }
            },
        });
    }

    loadFeedbackList(self) {
        const width = $(window).width();
        const height = $(document).height();

        $.ajax({
            type: "GET",
            url: `${self.path}/feedback/include/getFeedbackList.php`,
            data: {
                feedback: self.jsonFile,
                width,
                height,
            },
            contentType: "application/json",
            async: true,
        }).done(function (response) {
            $(".feedback-comment-list").html("");
            $("body").find(".feedback-pin").remove();

            if (response.length > 1) {
                const { marks, comments, count, pending } = jQuery.parseJSON(response)[0];

                $("body").find(".feedback-pin").remove();
                $(".feedback-comment-list").html("");

                self.insertMarksAndCommentsFromRaw(self, marks, comments);


                $("#textarea-comment").prop("disabled", true);
                $("#textarea-comment").attr("data-index", "");
                $("#textarea-comment").val("");

                $(".sidebar-feedback").hide();
                $('.sidebar-feedback[visible="visible"]').show();

                $(".feedback-pin").hide();
                $('.feedback-pin[visible="visible"]').show();

                const buttonFeedback = $(document).find('[data-action="feedback"]');
                buttonFeedback.addClass("has-feedback");

                if (parseInt(pending) > 0) {
                    buttonFeedback.removeClass("no-pending");
                    if (buttonFeedback.find(".badge").length) {
                        buttonFeedback.find(".badge").text(pending);
                    } else {
                        buttonFeedback.append(`<span class="badge badge-warning">${pending}</span>`);
                    }
                } else {
                    buttonFeedback.addClass("no-pending");
                    if (buttonFeedback.find(".badge").length) {
                        buttonFeedback.find(".badge").remove();
                    }
                }
            } else {
                $(document)
                    .find('[data-action="feedback"]')
                    .removeClass("has-feedback");
                $(document)
                    .find('[data-action="feedback"]')
                    .removeClass("no-pending");

                if ($(document).find('[data-action="feedback"]').find(".badge").length) {
                    $(document)
                        .find('[data-action="feedback"]')
                        .find(".badge")
                        .remove();
                }
            }
        });
    }

    toggleFeedbackPanelHandler(self) {
        return function(e) {
            e.preventDefault();
            if ($("#feedback-wrapper").hasClass("toggled")) {
                $('.feedback-pin[visible="visible"]').toggle();
                $("#feedback-wrapper").toggleClass("toggled");
                $("body").toggleClass("show-feedback");
                e.stopPropagation();
            } else {
                self.closeFeedbackHandler(self)(e)
            }
        }
    }

    closeFeedbackHandler(self) {
        return function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("#feedback-wrapper").toggleClass("toggled");
            $("body").toggleClass("show-feedback");
            $('.feedback-pin[visible="visible"]').hide();

            if ($("body").hasClass("active_feedback")) {
                $("body").toggleClass("active_feedback");
            }

            if ($(".btn-placepin").hasClass("active")) {
                $(".btn-placepin").removeClass("active");
            }
            $(".btn-placepin").find("h3").html("Place a pin");

            const textAreaComment = $("#textarea-comment");
            const statusTextarea = textAreaComment.prop("disabled");

            if (!statusTextarea) {
                const feedbackPinTemp = textAreaComment.attr("data-index");
                textAreaComment.prop("disabled", true);
                textAreaComment.attr("data-index", "");
                textAreaComment.val("");

                if (textAreaComment.attr("data-status") == 0) {
                    $(`#feedback-pin${feedbackPinTemp}`).remove();
                }
            }
        }
    }

    inactiveFeedback() {
        $("body").toggleClass("active_feedback");
        $(".btn-placepin").toggleClass("active");
        $(".btn-placepin").find("h3").html("Place a pin");
    }

    resetCommentTextArea(count) {
        $("#textarea-comment").prop("disabled", false);
        $("#textarea-comment").attr("data-index", count);
        $("#textarea-comment").attr("data-status", "0");
        $("#textarea-comment").focus();
    }

    createFeedbackMark(self, { leftPos, topPos, leftStyle, topStyle, count }) {
        return `
            <div
                class="feedback-pin"
                id="feedback-pin${count}"
                ${self.feedbackIndexKey}="${count}"
                ${self.leftPositionKey}="${leftPos}"
                ${self.topPositionKey}="${topPos}"
                style="left: ${leftStyle}; top: ${topStyle};"
            >
                <span>${count}</span>
            </div>
        `
    }

    insertFeedbackMarkOnFeedbackElement(self, count, e) {
        const { left, top, width, height } = e.target.parentNode.getBoundingClientRect();
        const { pageX, pageY } = e;
        const percentageY = pageY - top;
        const percentageX = pageX - left;

        $(e.target).parent().append(self.createFeedbackMark(self, {
            leftPos: percentageX,
            topPos: percentageY,
            leftStyle: `calc(${percentageX}px - 15px)`,
            topStyle: `calc(${percentageY}px - 15px)`,
            count
        }));

        self.resetCommentTextArea(count);
        self.inactiveFeedback();
    }

    insertFeedbackMarkOnBody(self, count, e) {
        $("body").append(self.createFeedbackMark(self, {
            leftPos: (e.pageX * 100) / $(window).width(),
            topPos: (e.pageY * 100) / $(document).height(),
            leftStyle: `${e.pageX}px`,
            topStyle: `${e.pageY}px`,
            count
        }));
        self.resetCommentTextArea(count);
        self.inactiveFeedback();
    }

    bodyClickHandler(self) {
        return function(e) {
            if ($(this).hasClass("active_feedback")) {
                const parent = $(e.target).parents(".feedbackPanel");

                if (!$(parent).hasClass("feedbackPanel")) {
                    const count = $(".feedback-comment-list .sidebar-feedback").length + 1;

                    if (self.feedbackElements) {
                        if (self.feedbackElements.find(el => e.target.isSameNode(el))) {
                            self.insertFeedbackMarkOnFeedbackElement(self, count, e);
                        } else {
                            self.closeFeedbackHandler(self)(e);
                        }
                    } else {
                       self.insertFeedbackMarkOnBody(self, count, e)
                    }
                }
            } else {
                if (!$("#feedback-wrapper").hasClass("toggled")) {
                    const parent = $(event.target);

                    if (
                        parent.parent('[data-action="feedback"]').length == 0 &&
                        !$(parent).parents(".feedbackPanel").hasClass("feedbackPanel") &&
                        !$(parent).parents(".feedback-pin").hasClass("feedback-pin") &&
                        !$(parent).hasClass("feedback-pin") &&
                        !$(parent).parents(".swal-overlay").hasClass("swal-overlay")
                    ) {
                        $(".feedback-close").click();
                    }
                }
            }
        }
    }

    feedbackPinClickHandler(self) {
        return function (e) {
            e.preventDefault();

            if ($("#feedback-wrapper").hasClass("toggled")) {
                $("#feedback-wrapper").removeClass("toggled");
            }

            const index = $(this).attr(self.feedbackIndexKey);

            $(".feedback-comment-list")
                .find(`[data-index="${index}"]`)
                .find(".dropdown-toggle")
                .dropdown();

            if ($(this).hasClass("active")) {
                $(".feedback-pin").removeClass("active");
                $(".sidebar-feedback").removeClass("active");
            } else {
                $(".feedback-pin").removeClass("active");
                $(".sidebar-feedback").removeClass("active");

                $(this).addClass("active");
                $(`.sidebar-feedback[data-index="${index}"]`).addClass("active");
            }
        }
    }

    feedbackPinMouseOverEnterHandler(self) {
        return function() {
            if ($("#feedback-wrapper").hasClass("toggled")) {
                $("#feedback-wrapper").removeClass("toggled");
            }

            const index = $(this).attr(self.feedbackIndexKey);

            $(this).hover(
                function () {
                    $(".feedback-comment-list").find(`[data-index="${index}"]`).addClass("hover");
                },
                function () {
                    $(".feedback-comment-list").find(`[data-index="${index}"]`).removeClass("hover");
                }
            );
        }
    }

    sidebarFeedbackClickHandler(self) {
        return function (e) {
            e.preventDefault();
            const feedbackPinTopPosition = $(`#feedback-pin${$(this).attr("data-index")}`).attr(self.topPositionKey);
            const scrollTop = (feedbackPinTopPosition * $(document).height()) / 100 - self.offsetTop
            const index = $(this).attr("data-index");

            $("html, body").animate({ scrollTop }, 600);


            if ($(this).hasClass("active")) {
                $(".feedback-pin").removeClass("active");
                $(".sidebar-feedback").removeClass("active");
            } else {
                $(".feedback-pin").removeClass("active");
                $(".sidebar-feedback").removeClass("active");

                $(this).addClass("active");
                $(`#feedback-pin${index}`).addClass("active");
            }
        }
    }

    sidebarFeedbackMouseEnterHandler() {
        return function () {
            const index = $(this).attr("data-index");

            $(this).hover(
                function () {
                    $(`#feedback-pin${index}`).addClass("hover");
                },
                function () {
                    $(`#feedback-pin${index}`).removeClass("hover");
                }
            );
        }
    }

    cancelFeedbackButtonClickHandler(self) {
        return function() {
            const textarea = $("#textarea-comment");
            const feedbackIndex = textarea.attr("data-index");
            textarea.prop("disabled", true);
            textarea.attr("data-index", "");
            textarea.val("");

            if (textarea.attr("data-status") == 0) {
                $(`#feedback-pin${feedbackIndex}`).remove();
            }
        }
    }

    sendFeedbackButtonClickHandler(self) {
        return function () {
            const textarea = $("#textarea-comment").val();

            if (textarea.length > 0) {
                const feedback = $("#textarea-comment");
                const index = $(feedback).attr("data-index");
                const feedbackString = [{
                    feedbackIndex: index,
                    comment: $(feedback).val(),
                    status: $(feedback).attr("data-status"),
                    leftPosition: $(`#feedback-pin${index}`).attr(self.leftPositionKey),
                    topPosition: $(`#feedback-pin${index}`).attr(self.topPositionKey),
                    parentId: $(`#feedback-pin${index}`).parent().attr('id')
                }];

                $.ajax({
                    type: "POST",
                    url: `${self.path}/feedback/include/saveFeedback.php`,
                    data: {
                        feedbackString: JSON.stringify(feedbackString),
                        jsonFile: self.jsonFile,
                    },
                    success: function () {
                        $(".resolved-status").prop("checked", false);
                        $(".btn-placepin").find("h3").html("Place a pin");
                        self.loadFeedbackList(self);
                    },
                    error: function () {
                        alert("An error occurred while saving the record.");
                    },
                });
            }
        }
    }

    resolveStatusChangeHandler(self) {
        return function() {
            const feedbackIndex = $("#textarea-comment").attr("data-index");
            $("#textarea-comment").prop("disabled", true);
            $("#textarea-comment").attr("data-index", "");
            $("#textarea-comment").val("");

            if ($("#textarea-comment").attr("data-status") == 0) {
                $(`#feedback-pin${feedbackIndex}`).remove();
            }

            $(".sidebar-feedback").hide();
            $(".sidebar-feedback").attr("visible", "hidden");

            $(".feedback-pin").hide();
            $(".feedback-pin").attr("visible", "hidden");

            if ($(this).is(":checked")) {
                $(".sidebar-feedback[data-status='2']").attr("visible", "visible");
                $('.sidebar-feedback[visible="visible"]').show();

                $(".feedback-pin_resolved").attr("visible", "visible");
                $('.feedback-pin[visible="visible"]').show();
            } else {
                $(".sidebar-feedback[data-status='1']").attr("visible", "visible");
                $('.sidebar-feedback[visible="visible"]').show();

                $(".feedback-pin").attr("visible", "visible");
                $(".feedback-pin_resolved").attr("visible", "hidden");
                $('.feedback-pin[visible="visible"]').show();
            }
        }
    }

    buttonResolveClickHandler(self) {
        return function() {
            const feedback = $(this).parents(".sidebar-feedback");
            const index = $(feedback).attr("data-index");

            const feedbackString = [{
                feedbackIndex: index,
                comment: $(feedback).find("textarea").val(),
                status: 2,
                leftPosition: $(`#feedback-pin${index}`).attr(self.leftPositionKey),
                topPosition: $(`#feedback-pin${index}`).attr(self.topPositionKey),
                parentId: $(`#feedback-pin${index}`).parent().attr('id')
            }];

            $.ajax({
                type: "POST",
                url: `${self.path}/feedback/include/statusFeedback.php`,
                data: {
                    feedbackString: JSON.stringify(feedbackString),
                    jsonFile: self.jsonFile,
                },
            }).done(function () {
                self.loadFeedbackList(self);
            });
        }
    }

    buttonUnresolvedClickHandler(self) {
        return function() {
            const feedback = $(this).parents(".sidebar-feedback");
            const index = $(feedback).attr("data-index");

            const feedbackString = [{
                feedbackIndex: index,
                comment: $(feedback).find("textarea").val(),
                status: 1,
                leftPosition: $(`#feedback-pin${index}`).attr(self.leftPositionKey),
                topPosition: $(`#feedback-pin${index}`).attr(self.topPositionKey),
                parentId: $(`#feedback-pin${index}`).parent().attr('id')
            }];

            $.ajax({
                type: "POST",
                url: `${self.path}/feedback/include/statusFeedback.php`,
                data: {
                    feedbackString: JSON.stringify(feedbackString),
                    jsonFile: self.jsonFile,
                },
            }).done(function () {
                $(".resolved-status").prop("checked", false);
                self.loadFeedbackList(self);
            });
        }
    }

    buttonDeleteFeedbackClickHandler(self) {
        return function() {
            const feedback = $(this).parents(".sidebar-feedback");
            const index = $(feedback).attr("data-index");

            const feedbackString = [{
                feedbackIndex: index,
                comment: $(feedback).find("textarea").val(),
                status: $(feedback).attr("data-status"),
                leftPosition: $("#feedback-pin" + index).attr(self.leftPositionKey),
                topPosition: $("#feedback-pin" + index).attr(self.topPositionKey),
                parentId: $(`#feedback-pin${index}`).parent().attr('id')
            }];

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this feedback!",
                icon: "error",
                buttons: true,
                dangerMode: true,
                buttons: {
                    cancel: "Oh wait, no!",
                    catch: {
                        text: "Yes, i'm sure!",
                        value: "ok",
                    },
                },
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: `${self.path}/feedback/include/deleteFeedback.php`,
                        data: {
                            feedbackString: JSON.stringify(feedbackString),
                            jsonFile: self.jsonFile,
                        },
                    }).done(function () {
                        self.loadFeedbackList(self);
                    });
                }
            });
        }
    }

    buttonEditFeedbackClickHandler() {
        return function() {
            const feedback = $(this).parents(".sidebar-feedback");

            $("#textarea-comment").prop("disabled", false);
            $("#textarea-comment").attr("data-index", $(feedback).attr("data-index"));
            $("#textarea-comment").attr("data-status", $(feedback).attr("data-status"));
            $("#textarea-comment").val($(feedback).find(".text-comment").html());
            $("#textarea-comment").focus();
        }
    }

    activeFeedbackButtonClickHandler() {
        return function(e) {
            const count = $("#textarea-comment").attr("data-index");

            if (count == 0 || typeof count === "undefined") {
                $(this).toggleClass("active");
                $("body").toggleClass("active_feedback");

                if ($(this).hasClass("active")) {
                    $(this).find("h3").html("Cancel pin");
                } else {
                    $(this).find("h3").html("Place a pin");
                }
            } else {
                $("#textarea-comment").focus();
            }

            $(".feedback-pin").removeClass("active");
            $(".sidebar-feedback").removeClass("active");
        }
    }

    addElements(self) {
        const sidebarNav = self.createSidebarNav();
        const commentsList = self.createCommentsList();
        const scrollContainer = self.createScrollContainer(commentsList);
        const sidebarWrapper = self.createSidebarWrapper(sidebarNav, scrollContainer);
        const feedbackWrapper = self.createFeedbackWrapper(self, sidebarWrapper);

        document.body.prepend(feedbackWrapper);

        if (!self.disableOverlay) {
            document.body.prepend(self.createOverlay());
        }

        $(".sidebar-nav").html(self.createHeader());
        $(".sidebar-nav").append(self.createToolbar(self));
        $(".sidebar-nav").append(self.createTextarea());
    }

    addHandlers(self) {
        $('[data-action="feedback"]').click(self.toggleFeedbackPanelHandler(self));
        $("body").on("click", self.bodyClickHandler(self));
        $("body").on("click", ".feedback-close", self.closeFeedbackHandler(self));
        $("body").on("click", ".feedback-pin", self.feedbackPinClickHandler(self));
        $("body").on("mouseover mouseenter", ".feedback-pin", self.feedbackPinMouseOverEnterHandler(self));
        $("body").on("click", ".sidebar-feedback", self.sidebarFeedbackClickHandler(self));
        $("body").on("mouseenter", ".sidebar-feedback", self.sidebarFeedbackMouseEnterHandler());
        $("body").on("click", ".btn-cancel_feedback", self.cancelFeedbackButtonClickHandler(self));
        $("body").on("click", ".btn-send_feedback", self.sendFeedbackButtonClickHandler(self));
        $("body").on("change", ".resolved-status", self.resolveStatusChangeHandler(self));
        $("body").on("click", ".btn-resolve", self.buttonResolveClickHandler(self));
        $("body").on("click", ".btn-unresolved", self.buttonUnresolvedClickHandler(self));
        $("body").on("click", ".btn-delete_feedback", self.buttonDeleteFeedbackClickHandler(self));
        $("body").on("click", ".btn-edit_feedback", self.buttonEditFeedbackClickHandler());
    }

    init(parameters) {
        if (parameters) {
            this.path = parameters.path;
        }

        this.addElements(this);
        this.initialFeedbackLoad(this, parameters);
        this.addHandlers(this);
    }
}

var feedbackClass = FeedbackModule

function countFeedback() {}
