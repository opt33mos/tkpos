<template>
  <div class="container-fluid py-5">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <span class="card-title">Users</span>

          <div class="card-tools">
            <button
              class="btn btn-success float-right btn-sm"
              data-toggle="modal"
              data-target="#AddUser"
              @click="newEntry"
            >
              Add User
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
                <th>Username</th>
                <th>Role</th>
                <th>Date Created</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users.data" :key="user.id">
                <td v-show="false">{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.username }}</td>
                <td>{{ user.role | capitalize }}</td>
                <td>{{ user.created_at | DateFormat }}</td>
                <td>
                  <a href="#">
                    <i class="fa fa-edit" @click="editUser(user)"></i>
                  </a>
                  /
                  <a href="#" @click="deleteUser(user.id)">
                    <i class="fa fa-trash red"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <pagination :data="users" @pagination-change-page="pagination">
            <!-- <span slot="prev-nav">&lt; Previous</span>
              <span slot="next-nav">Next &gt;</span> -->
          </pagination>
        </div>
      </div>
      <!-- /.card -->

      <!-- Modal -->
      <div
        class="modal fade"
        id="AddUser"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">
                Update User's Information
              </h5>
              <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">
                Add New User
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
            <form @submit.prevent="editmode ? updateUser() : SaveUser()">
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

                <div class="form-group">
                  <label>Username</label>
                  <input
                    v-model="form.username"
                    type="text"
                    name="username"
                    autocomplete="off"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('username'),
                    }"
                  />
                  <has-error :form="form" field="username"></has-error>
                </div>

                <div class="form-group">
                  <label>Select Role</label>
                  <select
                    v-model="form.role"
                    name="role"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('role'),
                    }"
                  >
                    <option value="" disabled>--Select Role--</option>
                    <option value="admin">Admin</option>
                    <option value="cashier">Cashier</option>
                  </select>
                  <has-error :form="form" field="role"></has-error>
                </div>

                <div class="form-group">
                  <label>Password</label>
                  <input
                    v-model="form.password"
                    type="password"
                    name="password"
                    autocomplete="off"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('password'),
                    }"
                  />
                  <has-error :form="form" field="password"></has-error>
                </div>

                <div class="form-group">
                  <label>Confirm Password</label>
                  <input
                    v-model="form.confirm_password"
                    type="password"
                    name="confirm_password"
                    autocomplete="off"
                    class="form-control"
                    :class="{
                      'is-invalid': form.errors.has('confirm_password'),
                    }"
                  />
                  <has-error :form="form" field="confirm_password"></has-error>
                </div>
              </div>
              <div class="modal-footer">
                <button v-show="editmode" type="submit" class="btn btn-primary">
                  Update User
                </button>
                <button
                  v-show="!editmode"
                  type="submit"
                  class="btn btn-primary"
                >
                  Save User
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
    this.loadUsers();
    Fire.$on("LoadTable", () => {
      this.loadUsers();
    });
  },
  data() {
    return {
      editmode: false,
      users: {},
      form: new Form({
        id: "",
        name: "",
        username: "",
        role: "",
        password: "",
        confirm_password: "",
      }),
    };
  },
  methods: {
    newEntry() {
      this.form.reset();
      $("#AddUser").modal("show");
      this.editmode = false;
    },
    editUser(user) {
      this.form.reset();
      this.editmode = true;
      $("#AddUser").modal("show");
      this.form.fill(user);
    },
    SaveUser() {
      this.$Progress.start();
      this.form
        .post("api/user")
        .then(() => {
          Fire.$emit("LoadTable");
          $("#AddUser").modal("hide");
          Toast.fire({
            icon: "success",
            title: "User Successfully Save",
          });
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    updateUser() {
      this.$Progress.start();
      this.form
        .put("api/user/" + this.form.id)
        .then(() => {
          $("#AddUser").modal("hide");
          Toast.fire({
            icon: "success",
            title: "User Successfully Updated",
          });
          Fire.$emit("LoadTable");
          this.$Progress.finish();
        })
        .catch(() => {
          Swal.fire("Warning!", "Something went wrong.", "warning");
          this.$Progress.fail();
        });
    },
    deleteUser(id) {
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
            .delete("api/user/" + id)
            .then(() => {
              Fire.$emit("LoadTable");
              Toast.fire({
                icon: "info",
                title: "User Successfully Deleted",
              });
              this.$Progress.finish();
            })
            .catch(() => {
              this.$Progress.fail();
            });
        }
      });
    },
    loadUsers() {
      axios.get("api/user").then(({ data }) => {
        this.users = data;
      });
    },
    pagination(page = 1) {
      axios.get("api/user?page=" + page).then((respponse) => {
        this.users = respponse.data;
      });
    },
  },
};
</script>
