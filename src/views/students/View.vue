<template>
<div class="card p-5">
    <div class="card-header">
        <h2>Student List</h2>
        <RouterLink to="/students/create" class="btn btn-primary float-end">Create</RouterLink>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Gpa</th>
                    <th scope="col">Address</th>
                    <th scope="col">Image</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody v-if="this.students.length > 0">
                <tr v-for="(student,index) in this.students" :key="index">
                    <th scope="row">{{ student.id }}</th>
                    <td>{{ student.name }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.phone }}</td>
                    <td>{{ student.gpa }}</td>
                    <td>{{ student.address}}</td>
                    <td>
                       <img :src="`/images/student/${student.image}`" alt="no" class="imgSize">

                     <!-- <img :src="`/images/student/${student.image}`" alt="" class="imgSize"> -->
                    </td>
                    <td class="text-center">
                      <RouterLink :to="{path:'/students/edit/'+student.id}" class="btn btn-success me-2">Edit</RouterLink>
                      <RouterLink :to="{path:'/students/delete/'+student.id}" class="btn btn-danger">Delete</RouterLink>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr >
                    <th collapse="8">No data</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>
<script>
import axios from 'axios';
export default {
    name:"students",
    data(){
        return{
            students:[]
        }
    },
    mounted() {
        console.log('liza');
        this.getStudents();
    },
    methods: {
        getStudents(){
           axios.get('http://127.0.0.1:8002/api/student').then(res=>{
             this.students=res.data.students;
             console.log(this.students);
             
           });
        }
    },
}
</script>
<style scoped>
 .imgSize{
     width:80px;
     height:80px;
 }
</style>