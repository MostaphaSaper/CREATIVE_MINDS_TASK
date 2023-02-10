<script setup>
import { onMounted, reactive, ref } from '@vue/runtime-core';
import { Form, Field } from 'vee-validate';
import * as yup from 'yup';
import axios from 'axios';
import {useToastr} from '../../toaster.js';

const toastr = useToastr();
const phoneRegExp = /^((\\+[1-9]{1,4}[ \\-]*)|(\\([0-9]{2,3}\\)[ \\-]*)|([0-9]{2,4})[ \\-]*)*?[0-9]{3,4}?[ \\-]*[0-9]{3,4}?$/

    const formValues = ref();
    const deletedUserID = ref(null);
    const users = ref([]);
    const form = ref(null);

    const getUsers = () => {
        axios.get('/api/users')
        .then((response)=> {
            users.value = response.data;
        })
    }

    const editing = ref(false);

    const addUser = () => {
        editing.value = false;
        $('#userModal').modal('show');
    }

    const updateUser = (values,{setErrors}) => {
        axios.put('/api/users/'+formValues.value.id,values)
        .then((response)=>{
            const index = users.value.findIndex(user => user.id === response.data.id);
            users.value[index] = response.data;
            $('#userModal').modal('hide');
            toastr.success('User Updated Successfully');
        }).catch((error) => {
             setErrors(error.response.data.errors);
        })
    }

    const handleSubmit = (values,actions) => {
        if(editing.value){
            updateUser(values,actions);
        } else{
            createUser(values,actions);
        }
    }

    const createUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().required().min(5),
        phone: yup.string().required().matches(phoneRegExp,'Phone must be numbers').min(11),
    });

    const editUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        phone: yup.string().required().matches(phoneRegExp,'Phone must be numbers').min(11),
        password: yup.string().when((password,schema) => {
            return password ? schema.required().min(8) : schema;
        }),



    });

    const createUser = (values,{resetForm,setErrors}) =>{
        axios.post('/api/users',values)
        .then((response)=> {
            users.value.unshift(response.data);
            $('#userModal').modal('hide');
             toastr.success('User Created Successfully');
            resetForm();
        })
        .catch((error) =>{
             setErrors(error.response.data.errors);
        })
    }

    const editUser = (user) =>{
        editing.value = true;
        form.value.resetForm();
        $('#userModal').modal('show');
        formValues.value = {
            id: user.id,
            email: user.email,
            name: user.name,
            phone: user.phone,
        };
    }

const SubmitDeletion = () =>{
    axios.delete(`/api/users/${deletedUserID.value}`)
    .then(() => {
         $('#deleteModal').modal('hide');
        toastr.success('User Deleted Succefully');
        getUsers();
    });
}

const deletUqser = (user) =>{
    deletedUserID.value = user.id;
    $('#deleteModal').modal('show');
}
    onMounted(()=>{
        getUsers();
        toastr.info('success');
    })
</script>

<template>
   <div class="content-header">
                <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Users </li>
                </ol>
                </div>
                </div>
                </div>
        </div>


        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4>Do you really want to delete this user??</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button @click.prevent="SubmitDeletion" type="button" class="btn btn-danger">Yes, Delete</button>
            </div>
            </div>
        </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <button @click="addUser" type="button" class="mb-3 btn btn-info" >
                    Add New User
                </button>
                <div class="card">
                    <div class="card-body">

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>phone</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user,index) in users" :key="user.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.phone }}</td>
                                    <td>
                                        <a href="#" @click.prevent="editUser(user)"><i class="fa fa-edit"></i></a>
                                        <a href="#" @click.prevent="deletUser(user)"><i class="fa fa-trash text-danger ml-4"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            <span v-if="editing">Edit User</span>
                            <span v-else>Create User</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <Form ref="form" @submit="handleSubmit"  :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }" :initial-values="formValues">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Name:</label>
                                <Field name="name" type="text" class="form-control" id="name"/>
                                <span class="text-danger">{{ errors.name }}</span>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-form-label">Email:</label>
                                <Field  name="email" type="email" class="form-control" id="email"/>
                                <span class="text-danger">{{ errors.email }}</span>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-form-label">Phone:</label>
                                <Field  name="phone" type="phone" class="form-control" id="phone"/>
                                <span class="text-danger">{{ errors.phone }}</span>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-form-label">Password:</label>
                                <Field name="password" type="password" class="form-control" id="password"/>
                                <span class="text-danger">{{ errors.password }}</span>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    </Form>
                </div>
            </div>
        </div>
</template>
