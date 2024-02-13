let categoryProducts = [];

$(document).ready(function () {
    $("#sorting-select").on('change', (e) => {
        sortProducts(e.target.value);
    });

    getCategories();

    const queryString = window.location.search;
    if (queryString) {
        const urlParams = new URLSearchParams(queryString);
        const category = urlParams.get('category');
        const sortBy = urlParams.get('sortBy');
        if (category) {
            getProducts(category);
        }
        if (sortBy) {
            $("#sorting-select").val(sortBy);
            sortProducts(sortBy);
        }
    }
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
        for (const [key, value] of Object.entries(JSON.parse(result))) {
            $("#categories-list").append(
                `<button type="button"
                         id="category-button-${value?.id}"
                         class="list-group-item list-group-item-action d-flex justify-content-between align-items-start"
                         onclick="getProducts(${value?.id})"
                         >
                        ${value?.name}
                    <span class="badge bg-primary rounded-pill">${value?.product_count}</span>
                </button>`
            )
        }
    });
};

const getProducts = (categoryId) => {
    window.history.replaceState(null, null, `?category=${categoryId}`);

    $('#products-container').empty();
    $("#categories-list").children().get().forEach(element => {
        element.classList.remove("list-group-item-primary");
    })
    // $(`#category-button-${categoryId}`)[0].classList.add("list-group-item-primary");

    performAjax('productsByCategory', (result) => {
        let products = JSON.parse(result);
        if (products.length) {
            renderProducts(products);
        }
        categoryProducts = products;
    }, [categoryId]);
};

const sortProducts = (sortParam, products = []) => {
    const queryString = window.location.search;
    if (queryString) {
        const urlParams = new URLSearchParams(queryString);
        const category = urlParams.get('category');
        window.history.replaceState(null, '', `?category=${category}&sortBy=${sortParam}`);
    }
    let sortFn;

    switch (sortParam) {
        case 'created_at':
            sortFn = (a, b) => new Date(a?.created_at) - new Date(b?.created_at);
            break;
        case 'price':
            sortFn = (a, b) => a?.price - b?.price;
            break;
        case 'name':
            sortFn = (a, b) => ('' + a?.name).localeCompare(b?.name);
            break;
        default:
            sortFn = (a, b) => a[sortParam] - b[sortParam];
    }

    if (!products.length) {
        products = categoryProducts;
    }

    products.sort(sortFn);
    renderProducts(products);
};

const renderProducts = (products) => {
    $('#products-container').empty();
    $("#products-container").append(
        `<br/><div class='row'></div>`
    );
    let rowCount = 1;

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
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal-${product.id}">Buy</button>
                            <div class="modal fade" id="productModal-${product.id}" tabindex="-1" aria-labelledby="productModalLabel-${product.id}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="productModalLabel-${product.id}">${product.name}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            The price is ${product.price}
                                            <br/>
                                            The date of creation is ${product.created_at}
                                            <br/>
                                            Probably, here will be more details :)
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`
        );

        rowCount++;
    });
};