<template>
<div class="container mt-5">
    <div class="card">
        <div class="card-head">
            <h1>Student Edit</h1>
        </div>
        <div class="card-body">
            <ul v-if="Object.keys(this.errorList).length>0" class="alert alert-warning">
                <li class="mb-0 ms-3" v-for="(error,index) in this.errorList" :key="index">
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
                  <img v-if="model.student.image" :src="model.student.image" alt="no" class="imgSize pt-3">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Address</label>
                <textarea class="form-control" v-model="model.student.address" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <button type="button" @click="updateData" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from "axios";
export default {
    name: "StudentEdit",
    data() {
        return {
            errorList: '',
            id: '',
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
    mounted() {
        // console.log(this.$route.params.id);
        this.id = this.$route.params.id;
        this.getStudentData(this.$route.params.id);
    },
    methods: {
        getStudentData(id) {
            axios.get(`http://127.0.0.1:8002/api/student/edit/${id}`).then(res => {
                    //console.log(res.data.student);
                    this.model.student = res.data.student
                })
                .catch(function (error) {
                    if (error.response) {
                        if (error.response.status == 404) {
                            alert(error.response.data.message);
                            window.history.back();
                        }
                    }
                })
        },
        onFileChange(event) {
            const file = event.target.files[0];
            //this.model.student.image = file;
            if (file) {
                this.model.student.image = file;
            }
        },

        // updateData() {
        //     var mythis=this;
        //     axios.post(`http://127.0.0.1:8002/api/student/update/${this.id}`, this.model.student,{
        //          headers: {
        //            'Content-Type': 'multipart/form-data'
        //          }
        //     }).then(res => {
        //         console.log(res.data);
        //         alert(res.data.message)
        //         this.errorList='';
        //         this.$refs.fileInput.value = null;

        //     })
        //     .catch(function (error){
        //        if(error.response){
        //          if (error.response.status == 422) {
        //             mythis.errorList=error.response.data.errors;
        //         } else if (error.response.status==402) {
        //             alert(error.response.data.message);
        //         } else {
        //             console.log('Error', error.message);
        //         }
        //        }
        //     })
        // }
        updateData() {
            var mythis = this;

            // Create a FormData object
            let formData = new FormData();

            // Append each field to the FormData object
            formData.append('name', this.model.student.name);
            formData.append('email', this.model.student.email);
            formData.append('phone', this.model.student.phone);
            formData.append('gpa', this.model.student.gpa);
            formData.append('address', this.model.student.address);

            // Append the image only if it exists
            if (this.model.student.image instanceof File) {
                formData.append('image', this.model.student.image);
            }
            axios.post(`http://127.0.0.1:8002/api/student/update/${this.id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                    console.log(res.data);
                    alert(res.data.message);
                    this.errorList = '';
                    this.$refs.fileInput.value = null;
                })
                .catch(function (error) {
                    if (error.response) {
                        if (error.response.status == 422) {
                            mythis.errorList = error.response.data.errors;
                        } else if (error.response.status == 402) {
                            alert(error.response.data.message);
                        } else {
                            console.log('Error', error.message);
                        }
                    }
                });
        }

    }
}
</script>
<style scoped>
.imgSize {
    width: 80px;
    height: 80px;
}
</style>