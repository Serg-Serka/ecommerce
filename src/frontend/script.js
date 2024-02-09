$(document).ready(function () {
    getCategories();
});

const performAjax = (route, successFn) => {
    $(document).ready(function () {
        $.ajax({
            type: 'post',
            url: '../backend/api.php',
            data: {
                route: route
            },
            success: successFn,
        });
    });
};

const getCategories = () => {
    performAjax('categories', (result) => {
        console.log(result);
        for (const [key, value] of Object.entries(JSON.parse(result))) {
            $("#categories-list").append(
                `<button type="button"
                         class="list-group-item list-group-item-action d-flex justify-content-between align-items-start"
                         onclick="getProducts(${value?.id}, this)"
                         >
                        ${value?.name}
                    <span class="badge bg-primary rounded-pill">${value?.product_count}</span>
                </button>`
            )
        }
    });
};


const getProducts = (categoryId, listElement) => {
    $("#categories-list").children().get().forEach(element => {
        element.classList.remove("list-group-item-primary");
    })
    listElement.classList.add("list-group-item-primary");

    performAjax('productsByCategory', (result) => {
        console.log(result);
    })


};