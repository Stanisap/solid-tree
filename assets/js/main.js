$(document).ready(function () {

    /**
     * Creates a new root with helping the technology ajax
     */
    $('#createRoot').on('submit', function (event) {
        event.preventDefault();
        const form = $(this);
        sendAjaxPostRequest(form, '#tree');
    });
});

/*====================================
* functions
*===================================== */

/**
 * Sends an ajax request on a server
 * @param element is the object of this element
 * @param id is id of this element
 */
function sendAjaxPostRequest(element, id) {
    $.post(
        element.attr('action'),
        element.serialize(),
        function (data) {
            $(id).html(data);
        }
    );

}

/**
 * Adds a new root with helping the technology ajax
 * @param element
 * @param parentId
 */
function addRoot(parentId) {
    $.post(
        '/add',
        {
            parent_id: parentId,
            title: 'Root',
            is_child: 1
        },
        function (data) {
            $('#tree').html(data);
        }
    );
}

/**
 * Removes a root and its children with helping the technology ajax
 * @param element
 * @param rootId
 */
function removeRoot(rootId, parentId) {
    const modal = $('#deleteModal');
    setTimeout(function () {
        let span = modal.find('span.text-danger');
        let count = span.text();
        let timerId = setInterval(function () {
            count--;
            if (count <= 0) {
                clearInterval(timerId);
                count = 30;
                removeAjax(rootId, parentId);
                modal.modal('hide');
            } else if (modal.attr('aria-hidden')) {
                clearInterval(timerId);
                count = 30;
            }

            span.html(count);
        }, 1000);
        $('#sendConfirmation').on('click', function () {
            removeAjax(rootId, parentId);
        });
    }, 500);
}

/**
 * Sends an ajax request for removing this roots
 * @param id
 * @param parentId
 */
function removeAjax(id, parentId) {
    $.post(
        '/delete',
        {
            id: id,
            parent_id: parentId
        },
        function (data) {
            $('#tree').html(data);
        }
    );
}

/**
 * Sends an ajax request to display child elements when on button clicked
 * @param id
 * @param parent_id
 */
function showChildren(id, parent_id) {
    $.post(
        '/show',
        {
            id: id,
            parent_id: parent_id,
        },
        function (data) {
            $('#tree').html(data);
        }
    );
}

/**
 * Sends an ajax request to hide child elements when on button clicked
 * @param id
 * @param parent_id
 */
function hideChildren(id, parent_id) {
    $.post(
        '/hide',
        {
            id: id,
            parent_id: parent_id
        },
        function (data) {
            $('#tree').html(data);
        }
    );
}

/**
 * Sends an ajax request to rename title of the node
 * @param id
 */
function renameNode(id) {
    $('#renameNode').on('click', function () {
        let title = $("#titleNode").val();
        $.post(
            '/rename',
            {
                id: id,
                title: title,
            },
            function (data) {
                $('#tree').html(data);
            }
        );
        $(this).off('click');
    });
}




