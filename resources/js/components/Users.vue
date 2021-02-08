<template>
  <div class="container-fluid py-5">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <span class="card-title">User List</span>

          <div class="card-tools">
            <button
              class="btn btn-success float-right btn-sm"
              data-toggle="modal"
              data-target="#AddUser"
            >
              <i class="fas fa-user-plus"></i> Add User
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Date Created</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users.data" :key="user.id">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.username }}</td>
                <td>{{ user.role | capitalize }}</td>
                <td>{{ user.created_at | DateFormat }}</td>
                <td>
                  <a href="#">
                    <i class="fa fa-edit" @click="editModal(user)"></i>
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
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="SaveUser">
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
                <button type="submit" class="btn btn-primary">Save User</button>
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
    // Fire.$on("LoadTable", () => {
    //   this.loadUsers();
    // });
  },
  data() {
    return {
      users: {},
      form: new Form({
        name: "",
        username: "",
        role: "",
        password: "",
        confirm_password: "",
      }),
    };
  },
  methods: {
    SaveUser() {
      console.log(this.form.password);
            console.log(this.form.confirm_password);
      this.$Progress.start();
      if (this.form.confirm_password != "" && this.form.password != "") {
        if (this.form.confirm_password == this.form.password) {
          Toast.fire({
            type: "success",
            title: "User Successfully Save",
          });
          // this.form
          //   .post("api/user")
          //   .then(() => {
          //     //Fire.$emit("LoadTable");
          //     $("#AddUser").modal("hide");
          //     Toast.fire({
          //       type: "success",
          //       title: "User Successfully Save",
          //     });
          //     // this.$Progress.finish();
          //   })
          //   .catch(() => {
          //     // this.$Progress.fail();
          //   });
        } else {
          Toast.fire({
            type: "warning",
            title: "Failed",
          });
        }
      }
    },
    loadUsers() {
      axios.get("api/user").then(({ data }) => {
        this.users = data;
      });
    },
  },
};
</script>
