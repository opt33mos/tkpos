<template>
  <div class="container-fluid py-5">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <span class="card-title">Categories</span>

          <div class="card-tools">
            <button
              class="btn btn-success float-right btn-sm"
              data-toggle="modal"
              data-target="#AddCategory"
              @click="newEntry"
            >
              Add New Category
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
              <tr v-for="category in categories.data" :key="category.id">
                <td v-show="false">{{ category.id }}</td>
                <td>{{ category.name }}</td>
                <td>
                  <a href="#">
                    <i class="fa fa-edit" @click="editCategory(category)"></i>
                  </a>
                  /
                  <a href="#" @click="deleteCategory(category.id)">
                    <i class="fa fa-trash red"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <pagination :data="categories" @pagination-change-page="pagination">
            <!-- <span slot="prev-nav">&lt; Previous</span>
              <span slot="next-nav">Next &gt;</span> -->
          </pagination>
        </div>
      </div>
      <!-- /.card -->

      <!-- Modal -->
      <div
        class="modal fade"
        id="AddCategory"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">
                Update Category
              </h5>
              <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">
                Add New Category
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
              @submit.prevent="editmode ? updateCategory() : SaveCategory()"
            >
              <div class="modal-body">
                <div class="form-group">
                  <label>Name</label>
                  <input
                    v-model="form.name"
                    type="text"
                    name="name"
                    autocomplete="off"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('name'),
                    }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
              </div>
              <div class="modal-footer">
                <button v-show="editmode" type="submit" class="btn btn-primary">
                  Update Category
                </button>
                <button
                  v-show="!editmode"
                  type="submit"
                  class="btn btn-primary"
                >
                  Save Category
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
    this.loadCategories();
    Fire.$on("LoadTable", () => {
      this.loadCategories();
    });
  },
  data() {
    return {
      editmode: false,
      categories: {},
      form: new Form({
        id: "",
        name: "",
      }),
    };
  },
  methods: {
    newEntry() {
      this.form.reset();
      $("#AddCategory").modal("show");
      this.editmode = false;
    },
    editCategory(category) {
      this.form.reset();
      this.editmode = true;
      $("#AddCategory").modal("show");
      this.form.fill(category);
    },
    SaveCategory() {
      this.$Progress.start();
      this.form
        .post("api/category")
        .then(() => {
          Fire.$emit("LoadTable");
          $("#AddCategory").modal("hide");
          Toast.fire({
            icon: "success",
            title: "New Category Successfully Save",
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    updateCategory() {
      this.$Progress.start();
      this.form
        .put("api/category/" + this.form.id)
        .then(() => {
          $("#AddCategory").modal("hide");
          Toast.fire({
            icon: "success",
            title: "Category Successfully Updated",
          });
          Fire.$emit("LoadTable");
          this.$Progress.finish();
        })
        .catch(() => {
          Swal.fire("Warning!", "Something went wrong.", "warning");
          this.$Progress.fail();
        });
    },
    deleteCategory(id) {
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
            .delete("api/category/" + id)
            .then(() => {
              Fire.$emit("LoadTable");
              Toast.fire({
                icon: "info",
                title: "Category Successfully Deleted",
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
            });
        }
      });
    },
    loadCategories() {
      axios.get("api/category").then(({ data }) => {
        this.categories = data;
      });
    },

    pagination(page = 1) {
      axios.get("api/category?page=" + page).then((respponse) => {
        this.categories = respponse.data;
      });
    },
  },
};
</script>
