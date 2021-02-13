<template>
    <div class="container-fluid py-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Products</span>

                    <div class="card-tools">
                        <button
                            class="btn btn-success float-right btn-sm"
                            data-toggle="modal"
                            data-target="#AddProduct"
                            @click="newEntry"
                        >
                            Add New Product
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th v-show="false">ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Size</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="product in products.data"
                                :key="product.id"
                            >
                                <td v-show="false">{{ product.id }}</td>
                                <td>{{ product.name }}</td>
                                <td>{{ product.category.name }}</td>
                                <td>{{ product.size.name }}</td>
                                <td>{{ product.price }}</td>
                                <td>
                                    <a href="#">
                                        <i
                                            class="fa fa-edit"
                                            @click="editProduct(product)"
                                        ></i>
                                    </a>
                                    /
                                    <a
                                        href="#"
                                        @click="deleteProduct(product.id)"
                                    >
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Modal -->
            <div
                class="modal fade"
                id="AddProduct"
                tabindex="-1"
                aria-labelledby="exampleModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5
                                v-show="editmode"
                                class="modal-title"
                                id="exampleModalLabel"
                            >
                                Update Product
                            </h5>
                            <h5
                                v-show="!editmode"
                                class="modal-title"
                                id="exampleModalLabel"
                            >
                                Add New Product
                            </h5>
                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form
                            @submit.prevent="
                                editmode ? updateProduct() : SaveProduct()
                            "
                        >
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Product</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        name="name"
                                        autocomplete="off"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'name'
                                            )
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="name"
                                    ></has-error>
                                </div>

                                <div class="form-group">
                                    <label>Category</label>
                                    <select
                                        v-model="form.category_id"
                                        name="category_id"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'category_id'
                                            )
                                        }"
                                    >
                                        <option value="" disabled
                                            >--Select Category--</option
                                        >
                                        <option
                                            v-for="data in categories"
                                            :key="data.id"
                                            :value="data.id"
                                            >{{ data.name }}</option
                                        >
                                    </select>
                                    <has-error
                                        :form="form"
                                        field="category_id"
                                    ></has-error>
                                </div>

                                <div class="form-group">
                                    <label>Size</label>
                                    <select
                                        v-model="form.size_id"
                                        name="size_id"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'size_id'
                                            )
                                        }"
                                    >
                                        <option value="" disabled
                                            >--Select Size--</option
                                        >
                                        <option
                                            v-for="data in sizes"
                                            :key="data.id"
                                            :value="data.id"
                                            >{{ data.name }}</option
                                        >
                                    </select>
                                    <has-error
                                        :form="form"
                                        field="size_id"
                                    ></has-error>
                                </div>

                                <div class="form-group">
                                    <label>Price</label>
                                    <input
                                        v-model="form.price"
                                        type="text"
                                        name="price"
                                        autocomplete="off"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'price'
                                            )
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="price"
                                    ></has-error>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button
                                    v-show="editmode"
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Update Product
                                </button>
                                <button
                                    v-show="!editmode"
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Save Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    created() {
        this.loadProducts();
        this.loadCategories();
        this.loadSizes();
        Fire.$on("LoadTable", () => {
            this.loadProducts();
        });
    },
    data() {
        return {
            editmode: false,
            products: {},

            categories: [],
            sizes: [],

            form: new Form({
                id: "",
                name: "",
                category_id: "",
                size_id: "",
                price: ""
            })
        };
    },
    methods: {
        newEntry() {
            this.form.reset();
            $("#AddProduct").modal("show");
            this.editmode = false;
        },
        editProduct(product) {
            this.form.reset();
            this.editmode = true;
            $("#AddProduct").modal("show");
            this.form.fill(product);
        },
        SaveProduct() {
            this.$Progress.start();
            this.form
                .post("api/product")
                .then(() => {
                    Fire.$emit("LoadTable");
                    $("#AddProduct").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: "New Product Successfully Save"
                    });
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        updateProduct() {
            this.$Progress.start();
            this.form
                .put("api/product/" + this.form.id)
                .then(() => {
                    $("#AddProduct").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: "Product Successfully Updated"
                    });
                    Fire.$emit("LoadTable");
                    this.$Progress.finish();
                })
                .catch(() => {
                    Swal.fire("Warning!", "Something went wrong.", "warning");
                    this.$Progress.fail();
                });
        },
        deleteProduct(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.isConfirmed) {
                    this.$Progress.start();
                    this.form
                        .delete("api/product/" + id)
                        .then(() => {
                            Fire.$emit("LoadTable");
                            Toast.fire({
                                icon: "info",
                                title: "Product Successfully Deleted"
                            });
                            this.$Progress.finish();
                        })
                        .catch(() => {
                            this.$Progress.fail();
                        });
                }
            });
        },
        loadProducts() {
            axios.get("api/product").then(({ data }) => {
                this.products = data;
            });
        },
        loadCategories() {
            axios.get("api/category").then(({ data }) => {
                this.categories = data.data;
            });
        },
        loadSizes() {
            axios.get("api/size").then(({ data }) => {
                this.sizes = data.data;
            });
        }
    }
};
</script>
