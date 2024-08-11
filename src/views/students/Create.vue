<template>
<div class="container mt-5">
    <div class="card">
        <div class="card-head">
            <h1>Student Create</h1>
        </div>
        <div class="card-body">
        <ul v-if="Object.keys(this.errorList).length>0" class="alert alert-warning">
            <li class="mb-0 ms-3" v-for="(error,index) in this.errorList" :key="index" >
               {{ error[0] }}
            </li>
        </ul>
            <div class="mb-3">
                <label for="" class="form-label">Name</label>
                <input type="text" v-model="model.student.name" class="form-control" id="" placeholder="enter your name">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Phone</label>
                <input type="text" v-model="model.student.phone" class="form-control" id="" placeholder="enter your phone">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Email</label>
                <input type="email" v-model="model.student.email" class="form-control" id="" placeholder="enter your email">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">GPA</label>
                <input type="text" v-model="model.student.gpa" class="form-control" id="" placeholder="enter gpa">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Image</label>
                <input type="file" @change="onFileChange" ref="fileInput" class="form-control" id="studentImage">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                <textarea class="form-control" v-model="model.student.address" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <button type="button" @click="saveData" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from "axios";
export default {
    name: "StudentCreate",
    data() {
        return {
            errorList:'',
            model: {
                student: {
                    name: '',
                    email: '',
                    phone: '',
                    gpa: '',
                    image: '',
                    address: '',
                }
            }
        }
    },
    methods: {
        onFileChange(event) {
            const file = event.target.files[0];
            this.model.student.image = file;
        },
        saveData() {
            var mythis=this;
            axios.post('http://127.0.0.1:8002/api/student/store', this.model.student,{
                 headers: {
                   'Content-Type': 'multipart/form-data'
                 }
            }).then(res => {
                console.log(res.data);
                alert(res.data.message)
                this.model.student = {
                    name: '',
                    email: '',
                    phone: '',
                    gpa: '',
                    image: '',
                    address: '',
                };
                this.errorList='';
                this.$refs.fileInput.value = null;

            })
            .catch(function (error){
               if(error.response){
                 if (error.response.status == 422) {
                    mythis.errorList=error.response.data.errors;
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
               }
            })
        }
    }
}
</script>
