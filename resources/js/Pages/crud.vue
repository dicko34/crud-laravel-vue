<script setup>
import $ from "jquery";
import "./../bootstrap";
import "material-icons/iconfont/material-icons.css";
import {
  useForm,
  Link,
  router,
} from "@inertiajs/vue3";
import { computed, ref, reactive, onMounted } from "vue";
// Vrs
const selectAll = reactive({ selected: false }),
      checkedBoxes = ref([]);

// methods 
const plusOne = computed({
  get: () => checkedBoxes.value.length > 0 &&  checkedBoxes.value != "all" ? "Selected" : "All",
})
let checkbox = $('table tbody input[type="checkbox"]');
onMounted(() => {
  checkbox = $('table tbody input[type="checkbox"]');

})
defineProps({
  errors: Object,
  users: Object,
})

// functions

let form = useForm({
  id: null,
  name: null,
  email: null,
  address: null,
  phone: null
})


function addChecked( arg) {
  if(checkedBoxes.value.includes("all")) {
    checkedBoxes.value.splice(checkedBoxes.value.indexOf("all"), 1);
  }
  if (checkedBoxes.value.includes(arg)) {
    checkedBoxes.value.splice(checkedBoxes.value.indexOf(arg), 1);
  } else {
    checkedBoxes.value.push(arg);
  }
  if (checkbox.length !== checkedBoxes.value.length) {
    selectAll.selected = false;
  } else {
    $('table tbody input[type="checkbox"]').each(function () {
      this.checked = true;
      checkedBoxes.value.push(Number($(this).val()));
    });
    selectAll.selected = true;
  }
}

const checkAll = function (event) {

  if (event.target.checked) {
    checkedBoxes.value = [];
    selectAll.selected = true;
    $('table tbody input[type="checkbox"]').each(function () {
      this.checked = true;
      checkedBoxes.value.push(Number($(this).val()));
    });
  } else {
    selectAll.selected = false;
    checkbox.each(function () {
      this.checked = false;
    });
    checkedBoxes.value = [];
  }
}
function shareData(obj) {
  form.id = obj.id;
  form.name = obj.name;
  form.email = obj.email;
  form.address = obj.address;
  form.phone = obj.phone;
}
function cleanData() {
  form.id = "";
  form.name = "";
  form.email = "";
  form.address = "";
  form.phone = "";
}
function submit() {
  router.post("create", form)
}

function edit(id) {
  router.put(`crud/${id}/edit/`, form)
}

function destroy(id) {
  id == "all" ? router.delete(`crud/all/delete`): router.delete(`crud/${id}/delete`);
}

</script>
<template lang="">
    <div class="container-xl">
      <div class="table-responsive">
          <div class="table-wrapper">
              <div class="table-title my-3">
                  <div class="row">
                      <div class="col-sm-3 ">
                          <h2>Manage </h2>
                    </div>
                    <div class="col-sm-9 text-right">
                          <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal" @click="cleanData()">
                  <i class="material-icons">&#xE147;</i>
                <span>Add New Employee</span>
              </a>
              <a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal" @click="[plusOne == 'All' ?addChecked('all') : '' ]">
                <i class="material-icons">&#xE15C;</i>
                <span>Delete {{plusOne}}</span>
              </a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                  <thead>
                      <tr>
                          <th>
                              <span class="custom-checkbox">
                    <input type="checkbox" id="selectAll" :checked="selectAll.selected" @click="checkAll($event)">
                  <label for="selectAll"></label>
                </span>
                        </th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                      <tr v-for="user in users.data" :key="user.id">
                        <td>
                            <span class="custom-checkbox">
                  <input type="checkbox" :id="'user_'+user.id" :name="'user_'+user.id" :value="user.id" @change="addChecked(user.id)">
                  <label :for="'user_'+user.id"></label>
                </span>
                        </td>
                        <td>{{user.name}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.address}}</td>
                        <td>{{user.phone}}</td>
                        <td>
                            <a href="#editEmployeeModal" class="edit" data-toggle="modal" @click="shareData(user)">
                  <i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i>
                </a>
                  <a href="#deleteEmployeeModal" class="delete" data-toggle="modal" @click=" checkedBoxes = [user.id];">
                  <i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i>
                </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="users.last_page > 1" class="clearfix">
                <div class="hint-text">Showing <b>{{ (users.total + users.per_page) -  (users.per_page * users.current_page) - (users.current_page == 1 ?(users.total - users.per_page) : '') }}</b> of <b>{{users.total}}</b> entries </div>
                <ul class="pagination flex">
                  
                    <li class="page-item row">
                        <div v-for="(link, p) in users.links" :key="p" class="col-auto py2" >
                            <Link v-if="link.url === null" :style="'display:none'" href="#" v-html="link.label"></Link>
                            <Link v-else class="page-link" :class="[link.active ? 'active' : 'disabled']" :href="link.url" v-html="link.label"></Link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="addEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form @submit.prevent="submit">
                <div class="modal-header">
                    <h4 class="modal-title">Add Employee</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input v-model="form.name" name="name" type="text" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.name'>{{errors.name}}</div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input v-model="form.email" :data-d="form.email" name="email" type="email" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.email'>{{errors.email}}</div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input v-model="form.address" name="address" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.address'>{{errors.address}}</div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input v-model="form.phone" name="phone" type="text" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.phone'>{{errors.phone}}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-success" value="Add">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form @submit.prevent=edit(form.id)>
                <div class="modal-header">
                    <h4 class="modal-title">Edit Employee{{form.id}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input v-model="form.name" name="name" type="text" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.name'>{{errors.name}}</div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input v-model="form.email" :data-d="form.email" name="email_e" type="email" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.email'>{{errors.email}}</div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input v-model="form.address" name="address_e" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.address'>{{errors.address}}</div>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input v-model="form.phone" name="phone_e" type="text" class="form-control" required>
                        <div class="text-danger text-xs" v-if='errors.phone'>{{errors.phone}}</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-info" value="Save">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal HTML -->
<div id="deleteEmployeeModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form @click.prevent="destroy( checkedBoxes)">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Employee </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete {{plusOne == "All" ? "all" : "these"}} Records?</p>
                    <p class="text-warning">
                        <small>This action cannot be undone.</small>
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                    <input type="submit" class="btn btn-danger" value="Delete">
                </div>
            </form>
        </div>
    </div>
</div>
</template>
<style lang="scss">
.material-icons {
  font-size: 18px;
  margin: 5px;
  vertical-align: middle;
}

.page-link {
  font-size: 13px;
  &.active {
    z-index: 3;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
  }
}
</style>