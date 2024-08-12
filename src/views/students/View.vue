<template>
<div class="card p-5">
    <div class="card-header">
        <h2>Student List</h2>
        <RouterLink to="/students/create" class="btn btn-primary float-end">Create</RouterLink>
    </div>
    <div class="card-body">
        <div>
            <input type="text" v-model="searchTerm" @keyup="searchData" placeholder="Search students..." class="form-control mb-3" />
        </div>
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
                <tr v-for="(student, index) in this.students" :key="index">
                    <th scope="row">{{ student.id }}</th>
                    <td>{{ student.name }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.phone }}</td>
                    <td>{{ student.gpa }}</td>
                    <td>{{ student.address }}</td>
                    <td>
                        <img v-if="student.image" :src="student.image"  class="imgSize" />
                        <span v-else>N/A</span>
                    </td>

                    <td class="text-center">
                        <RouterLink :to="{ path: '/students/edit/' + student.id }" class="btn btn-success me-2">Edit</RouterLink>
                        <button @click="deleteStudent(student.id)" class="btn btn-danger">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <th collapse="8">No data</th>
                </tr>
            </tbody>
        </table>
        <!-- <nav v-if="last_page > 1" aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{ disabled: current_page === 1 }">
                    <button class="page-link" @click="changePage(1)" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </button>
                </li>
                <li class="page-item" :class="{ disabled: current_page === 1 }">
                    <button class="page-link" @click="changePage(current_page - 1)" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </button>
                </li>
                <li v-for="page in last_page" :key="page" class="page-item" :class="{ active: page === current_page }">
                    <button class="page-link" @click="changePage(page)">{{ page }}</button>
                </li>
                <li class="page-item" :class="{ disabled: current_page === last_page }">
                    <button class="page-link" @click="changePage(current_page + 1)" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                </li>
                <li class="page-item" :class="{ disabled: current_page === last_page }">
                    <button class="page-link" @click="changePage(last_page)" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </button>
                </li>
            </ul>
        </nav> -->
        <nav v-if="last_page > 1" aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- First page and previous page buttons -->
                <li class="page-item" :class="{ disabled: current_page === 1 }">
                    <button class="page-link" @click="changePage(1)" aria-label="First">
                        <span aria-hidden="true">&laquo;&laquo;</span>
                    </button>
                </li>
                <li class="page-item" :class="{ disabled: current_page === 1 }">
                    <button class="page-link" @click="changePage(current_page - 1)" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </button>
                </li>
                <!-- Page numbers -->
                <li v-for="page in pagesToShow" :key="page" class="page-item" :class="{ active: page === current_page }">
                    <button class="page-link" @click="changePage(page)">{{ page }}</button>
                </li>
                <!-- Next page and last page buttons -->
                <li class="page-item" :class="{ disabled: current_page === last_page }">
                    <button class="page-link" @click="changePage(current_page + 1)" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                </li>
                <li class="page-item" :class="{ disabled: current_page === last_page }">
                    <button class="page-link" @click="changePage(last_page)" aria-label="Last">
                        <span aria-hidden="true">&raquo;&raquo;</span>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</div>
</template>

<script>
import axios from "axios";
export default {
    name: "students",
    data() {
        return {
            students: [],
            current_page: 1,
            last_page: 1,
            searchTerm: "",
        };
    },
    mounted() {
        //console.log('liza');
        this.getStudents();
    },
    computed: {
        pagesToShow() {
            const range = 3; // Number of pages to show around the current page
            let leftPages = [];
            let rightPages = [];
            let startPage, endPage;

            // Determine the pages to show on the left side
            if (this.current_page <= range) {
                startPage = 1;
                endPage = Math.min(this.last_page, range * 2 + 1);
            } else {
                startPage = this.current_page - range;
                endPage = this.current_page + range;
                if (endPage > this.last_page) {
                    endPage = this.last_page;
                }
            }
            for (let i = startPage; i <= endPage; i++) {
                leftPages.push(i);
            }

            // Determine the pages to show on the right side
            if (this.current_page >= this.last_page - range) {
                startPage = Math.max(1, this.last_page - range * 2);
                endPage = this.last_page;
            } else {
                startPage = Math.max(1, this.current_page - range);
                endPage = Math.min(this.last_page, this.current_page + range);
            }
            for (let i = endPage; i >= startPage; i--) {
                rightPages.push(i);
            }

            // Remove duplicate pages if the left and right ranges overlap
            let pages = [...new Set([...leftPages, ...rightPages])];

            // Ensure pages are sorted
            pages.sort((a, b) => a - b);

            return pages;
        },
    },
    methods: {
        getStudents(page = 1) {
            // axios.get(`http://127.0.0.1:8002/api/student?page=${page}`).then(res => {
            //     this.students = res.data.students;
            //     this.current_page = res.data.current_page;
            //     this.last_page = res.data.last_page;
            // });
            axios
                .get(`http://127.0.0.1:8002/api/student?page=${page}&search=${this.searchTerm}`)
                .then((res) => {
                    this.students = res.data.students;
                    this.current_page = res.data.current_page;
                    this.last_page = res.data.last_page;
                });
        },
        changePage(page) {
            if (page >= 1 && page <= this.last_page) {
                this.getStudents(page);
            }
        },
        searchData() {
            this.getStudents();
        },
        deleteStudent(id) {
            console.log(id);
            if (confirm("are you sure to delete this data")) {
                console.log(id);
                axios
                    .delete(`http://127.0.0.1:8002/api/student/delete/${id}`)
                    .then((res) => {
                        alert(res.data.message);
                        this.getStudents();
                    })
                    .catch(function (error) {
                        if (error.response) {
                            if (error.response.status == 404) {
                                alert(error.response.data.message);
                            }
                        }
                    });
            }
        },
    },
};
</script>

<style scoped>
.imgSize {
    width: 80px;
    height: 80px;
}
</style>
