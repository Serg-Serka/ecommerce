$(document).ready(function () {
    getCategories();
});

const performAjax = (route, successFn, params = []) => {
    $(document).ready(function () {
        $.ajax({
            type: 'post',
            url: '../backend/api.php',
            data: {
                route: route,
                params: JSON.stringify(params)
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
    $('#products-container').empty();
    $("#categories-list").children().get().forEach(element => {
        element.classList.remove("list-group-item-primary");
    })
    listElement.classList.add("list-group-item-primary");

    performAjax('productsByCategory', (result) => {
        console.log(JSON.parse(result));
        let products = JSON.parse(result);
        let rowCount = 1;
        $("#products-container").append(
            `<br/><div class='row'></div>`
        );

        products.forEach(product => {
            if (rowCount === 4) {
                $("#products-container").append(
                    `<br/><div class='row'></div>`
                );

                rowCount = 0;
            }
            $("#products-container .row").last().append(
                `<div class="col-4">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">The price: ${product.price}<br/> The date: ${product.created_at}</p>
                            <a href="#" class="btn btn-primary">More details</a>
                        </div>
                    </div>
                </div>`
            );

            rowCount++;
            console.log(product?.name);
        });


    }, [categoryId]);


};