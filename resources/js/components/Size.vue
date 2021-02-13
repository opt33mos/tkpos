<template>
  <div class="container-fluid py-5">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <span class="card-title">Size</span>

          <div class="card-tools">
            <button
              class="btn btn-success float-right btn-sm"
              data-toggle="modal"
              data-target="#AddSize"
              @click="newEntry"
            >
              Add New Size
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
              <tr v-for="size in sizes.data" :key="size.id">
                <td v-show="false">{{ size.id }}</td>
                <td>{{ size.name }}</td>
                <td>
                  <a href="#">
                    <i class="fa fa-edit" @click="editSize(size)"></i>
                  </a>
                  /
                  <a href="#" @click="deleteSize(size.id)">
                    <i class="fa fa-trash red"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <pagination :data="sizes" @pagination-change-page="pagination">
            <!-- <span slot="prev-nav">&lt; Previous</span>
              <span slot="next-nav">Next &gt;</span> -->
          </pagination>
        </div>
      </div>
      <!-- /.card -->

      <!-- Modal -->
      <div
        class="modal fade"
        id="AddSize"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">
                Update Size
              </h5>
              <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">
                Add New Size
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
            <form @submit.prevent="editmode ? updateSize() : SaveSize()">
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
                  Update Size
                </button>
                <button
                  v-show="!editmode"
                  type="submit"
                  class="btn btn-primary"
                >
                  Save Size
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
      $("#AddSize").modal("show");
      this.editmode = false;
    },
    editSize(size) {
      this.form.reset();
      this.editmode = true;
      $("#AddSize").modal("show");
      this.form.fill(size);
    },
    SaveSize() {
      this.$Progress.start();
      this.form
        .post("api/size")
        .then(() => {
          Fire.$emit("LoadTable");
          $("#AddSize").modal("hide");
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
    updateSize() {
      this.$Progress.start();
      this.form
        .put("api/size/" + this.form.id)
        .then(() => {
          $("#AddSize").modal("hide");
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
    deleteSize(id) {
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
        this.sizes = data;
      });
    },
  },
};
</script>
