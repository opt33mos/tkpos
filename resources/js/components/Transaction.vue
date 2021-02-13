<template>
  <div class="container-fluid py-5">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <span class="card-title">Transaction</span>
          <div class="card-tools">
            <button
              class="btn btn-success float-right btn-sm"
              data-toggle="modal"
              data-target="#mdlNewTransaction"
              @click="newEntry"
            >
              New Transaction
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
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr v-for="size in sizes.data" :key="size.id">
                <td v-show="false">{{ size.id }}</td>
                <td>{{ size.name }}</td>
                <td>
                  <a href="#">
                    <i class="fa fa-edit" @click="edit(size)"></i>
                  </a>
                  /
                  <a href="#" @click="deleteSize(size.id)">
                    <i class="fa fa-trash red"></i>
                  </a>
                </td>
              </tr> -->
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <!-- <pagination :data="sizes" @pagination-change-page="pagination">
         
          </pagination> -->
        </div>
      </div>
      <!-- /.card -->

      <!-- Modal -->
      <div
        class="modal fade"
        id="mdlNewTransaction"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">
                Update Transaction
              </h5>
              <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">
                New Transaction
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
            <form @submit.prevent="editmode ? Update() : Save()">
              <div class="modal-body">
                <div class="form-group">
                  <label>Products</label>
                  <select
                    v-model="form.product_id"
                    name="size_id"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('product_id'),
                    }"
                  >
                    <option value="" disabled>--Select Product--</option>
                    <option
                      v-for="data in products"
                      :key="data.id"
                      :value="data.id"
                    >
                      {{ data.name }}
                    </option>
                  </select>
                  <has-error :form="form" field="product_id"></has-error>
                </div>
                <div class="form-group">
                  <label>Size</label>
                  <select
                    v-model="form.size_id"
                    name="size_id"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('size_id'),
                    }"
                  >
                    <option value="" disabled>--Select Size--</option>
                    <option
                      v-for="data in sizes"
                      :key="data.id"
                      :value="data.id"
                    >
                      {{ data.name }}
                    </option>
                  </select>
                  <has-error :form="form" field="size_id"></has-error>
                </div>

                <div class="form-group">
                  <label>Quantity</label>
                  <input
                    v-model="form.qty"
                    type="number"
                    name="name"
                    autocomplete="off"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('qty'),
                    }"
                  />
                  <has-error :form="form" field="qty"></has-error>
                </div>
              </div>
              <div class="modal-footer">
                <button v-show="editmode" type="submit" class="btn btn-primary">
                  Update
                </button>
                <button
                  v-show="!editmode"
                  type="submit"
                  class="btn btn-primary"
                >
                  Save
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
    this.loadSizes();
    Fire.$on("LoadTable", () => {
      this.loadSizes();
    });
  },
  data() {
    return {
      editmode: false,
      sizess: [],
      products: [],
      sizes: {},
      form: new Form({
        id: "",
        name: "",
      }),
    };
  },
  methods: {
    newEntry() {
      this.form.reset();
      $("#mdlNewTransaction").modal("show");
      this.editmode = false;
    },
    edit(size) {
      this.form.reset();
      this.editmode = true;
      $("#mdlNewTransaction").modal("show");
      this.form.fill(size);
    },
    Save() {
      this.$Progress.start();
      this.form
        .post("api/size")
        .then(() => {
          Fire.$emit("LoadTable");
          $("#mdlNewTransaction").modal("hide");
          Toast.fire({
            icon: "success",
            title: "New Size Successfully Save",
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    update() {
      this.$Progress.start();
      this.form
        .put("api/size/" + this.form.id)
        .then(() => {
          $("#mdlNewTransaction").modal("hide");
          Toast.fire({
            icon: "success",
            title: "Size Successfully Updated",
          });
          Fire.$emit("LoadTable");
          this.$Progress.finish();
        })
        .catch(() => {
          Swal.fire("Warning!", "Something went wrong.", "warning");
          this.$Progress.fail();
        });
    },
    delete(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.isConfirmed) {
          this.$Progress.start();
          this.form
            .delete("api/size/" + id)
            .then(() => {
              Fire.$emit("LoadTable");
              Toast.fire({
                icon: "info",
                title: "Size Successfully Deleted",
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
            });
        }
      });
    },
    pagination(page = 1) {
      axios.get("api/size?page=" + page).then((respponse) => {
        this.sizes = respponse.data;
      });
    },
    loadSizes() {
      axios.get("api/size").then(({ data }) => {
        this.sizes = data.data;
      });
    },
    loadProducts() {
      axios.get("api/product").then(({ data }) => {
        this.products = data;
      });
    },
  },
};
</script>
