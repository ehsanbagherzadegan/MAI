<template>
    <div class="container" @keydown.alt.67="createUser">
        <div class="row justify-content-center mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{trans('user.cardTitle')}}</h3>
                <!-- /.card-tools -->
                    <div class="card-tools">
                        <el-button type="success"
                        size="mini"
                        v-focus
                        @click="createUser">{{trans('app.addBtnLbl')}} <i class="fas fa-plus fa-fw"></i></el-button>
                        <el-button type="primary"
                        size="mini"
                        @click="activeUser">{{trans('app.activeBtnLbl')}} <i class="fa fa-lightbulb"></i></el-button>
                        <el-button :type="btnType"
                        size="mini"
                        @click="loadDeletedUser">{{deletedBtnLbl}} <i :class="btnIcon"></i></el-button>
                    </div>                
                </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
				<el-table
					:data="list.filter(data => !search || data.username.toLowerCase().includes(search.toLowerCase())|| data.profile_name.toLowerCase().includes(search.toLowerCase())|| data.fullname.toLowerCase().includes(search.toLowerCase()))"
                    :default-sort = "{prop: 'username', order: 'descending'}"
                    :empty-text = "trans('app.no_data_found')"
					style="width: 100%" @selection-change="handleSelectionChange">
                    <el-table-column
                        type="selection"
                        width="55">
                    </el-table-column>
					<el-table-column
					  :label="trans('user.username')"
                      sortable
					  prop="username">
					</el-table-column>
                    <el-table-column
					  :label="trans('user.profileName')"
                      sortable
					  prop="profile_description">
					</el-table-column>
                    <!--<el-table-column prop="roles[0].description" :label="trans('user.roles')" sortable>                        
                    </el-table-column>-->
                    <el-table-column prop="fullname" :label="trans('userProfile.fullname')" sortable>                        
                    </el-table-column>
                    <el-table-column
                        prop="active"
                        :label="trans('user.status')"
                        width="100"
                        :filters="[{ text:trans('user.active'), value: true }, { text: trans('user.inActive'), value: false }]"
                        :filter-method="filterActive"
                        filter-placement="bottom-end">
                        <template slot-scope="scope">
                            <el-tag
                            :type="scope.row.active === true ? 'success' : 'danger'"
                            disable-transitions><span v-if="scope.row.active==1">{{trans('user.active')}}</span><span v-else>{{trans('user.inActive')}}</span></el-tag>
                        </template>
                    </el-table-column>
					<el-table-column class="float-left"
					  align="right">
					  <template slot="header" slot-scope="scope">
						<el-input
						  v-model="search"
						  :placeholder="trans('app.searchPlaceholder')"/>
                        <el-input name="id" type="hidden" v-model.number="form.id" autocomplete="off"></el-input>
					  </template>
					  <template slot-scope="scope" class="float-left">
                        <el-button
                        size="mini"
                        @click="editUser(scope.row)">{{trans('app.editBtnLbl')}} <i class="fa fa-edit blue"></i></el-button>
						<el-button
						  size="mini"
						  type="danger"
						  @click="deleteUser(scope.row)">{{trans('app.deleteBtnLbl')}} <i class="fa fa-trash red"></i></el-button>                        
					  </template>                    
					</el-table-column>
                    <infinite-loading
                    slot="append"
                    @infinite="infiniteHandler"
                    force-use-infinite-wrapper=".el-table__body-wrapper">
                        <div slot="no-more"></div>
                        <div slot="no-results"></div>
                    </infinite-loading>
				  </el-table>
                  <div class="block">
                       <!-- <el-pagination
                            background
                            layout="prev, pager, next"
                            prev-text="<"
                            next-text=">"
                            :page-size="1"
                            :total="10"
                            :data="tableData">
                        </el-pagination>-->                                  
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
    </div>
</template>
<script>
    import {errorMessage} from '../../utilities';
    export default 
    {
        data(){
            return{
				form: {
                    id: '',
                    active:'',
                    username: '',
                    email: '',
                    profile_name: '',
                    profileId:'',                    
                },          
				tableData:[],
                search: '',
                page:1,
                list: [],
                infiniteId: +new Date(),
                multipleSelection:[],
                showDeleted:0,
                deletedBtnLbl:trans('app.deletedBtnLbl'),
                btnType:'warning',
                btnIcon:'fas fa-user-times'
            }
        },
        methods :{
            /*
            |--------------------------------------------------------------------------
            | Lazy Load Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Is For Lazy Load Users Info
            |
            */    
            infiniteHandler($state) {
                axios.get("../api/users", {
                    params: {
                    page: this.page,
                    },
                }).then(({ data }) => {
                        if (data.data.length) {
                        this.page += 1;
                        this.list.unshift(...data.data.reverse());
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                });
            },
            /*
            |--------------------------------------------------------------------------
            | handleSelectionChange Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method handle Selection User Change
            |
            */  
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            /*
            |--------------------------------------------------------------------------
            | Filter Active user Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Filter Active Users
            |
            */  
            filterActive(value, row) {
                return row.active === value;
            },
            /*
            |--------------------------------------------------------------------------
            | Filter  user role Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Filter Active Users
            |
            */  
            filterRole(value, row) {
                return row.role === value;
            },
            /*
            |--------------------------------------------------------------------------
            | Load User Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Load Users Info
            |
            */    
            loadUser(){
                axios.get("../api/users").then(({data})=>(this.list = data.data)).catch((error)=>{
                    let msgErr = errorMessage(error.response.data.errors);
                    this.$message({                                           
                      message:msgErr,
                      center: true,
                      type: 'error'
                    }); 
                });
            },
            /*
            |--------------------------------------------------------------------------
            | Load User Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Load Users Info
            |
            */    
            loadDeletedUser(){
                if (this.showDeleted==0){
                        this.btnType ='primary';
                        this.showDeleted = 1;
                        this.btnIcon = 'fas fa-users';
                        this.deletedBtnLbl =trans('app.usersListBtnLbl');
                        axios.get("../api/users/trashed").then(({data})=>(this.list = data.data)).catch((error)=>{
                        let msgErr = errorMessage(error.response.data.errors);
                        this.$message({                                           
                        message:msgErr,
                        center: true,
                        type: 'error'
                        }); 
                    });
                }
                else{
                    this.showDeleted = 0;
                    this.btnType ='warning';
                    this.btnIcon = 'fas fa-user-times';
                    this.deletedBtnLbl =trans('app.deletedBtnLbl');
                    this.loadUser();
                }

            },
            /*
            |--------------------------------------------------------------------------
            | Active User Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Activate Selected Users
            |
            */  
            activeUser(){
               if(this.multipleSelection.length){
                var user_ids=[];
                this.multipleSelection.forEach((user, index) => {
                    if (user){
                        user_ids.push({
                            id: user.id,
                         });
                        }
                    });
                let userIds={ids:user_ids}
                axios.put('../api/users/batch/active/',userIds).then(response => {
                    this.$message({
                    type: 'success',
                    center: true,
                    message:response.data.message
                    });
                    Fire.$emit('AfterCrud');                  
                    }).catch((error) => {
                        let msgErr = errorMessage(error.response.data.errors);
                        this.$message({
                        type: 'error',
                        center: true,
                        message:msgErr              
                        });
                    }); 
               }
               else
               {
                   return false;
               }
            },
            /*
            |--------------------------------------------------------------------------
            | Go To Create User Page
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Load User Component
            |
            */      
            createUser(){
              this.$router.push({ name: 'create_users'});
            },            
            /*
            |--------------------------------------------------------------------------
            | Go To Edit User Page
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Load Edit User Component
            |
            */      
            editUser(record){
              this.$router.push({ name: 'edit_users', params: { userId: record.id,profile_id:record.profile_id } });
            },
            /*
            |--------------------------------------------------------------------------
            | Delete User Method
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method delete User info
            |
            */         
            deleteUser(record){
				  this.$confirm(this.noticTxt,this.warningAlert, {
                  confirmButtonText: this.confirmButtonText,
                  cancelButtonText: this.cancelButtonText,
                  type: 'warning',
                  center: true
                }).then((response) => {
                  axios.delete('../api/users/'+record.id)
                .then(response => {
                    this.loadUser();
                     this.$message({
                        type: 'success',
                        center: true,
                        message:response.data.message
                      });                
                }).catch((error) => {
                     let msgErr = errorMessage(error.response.data.errors);
                     this.$message({
                        type: 'error',
                        center: true,
                        message:msgErr                        
                      });
                    }); 
                }).catch(() => {
                  this.$message({
                    type: 'info',
                    center: true,
                    message: this.cancelAlert
                  });          
                });
            },
        },
        directives: {
            focus: {
                // directive definition
                inserted: function (el) {
                el.focus()
                }
            }
        },       
        created() {
            Fire.$on('AfterCrud',() => {               
             //this.LoadUser();
            });
        }
    }
</script>
<style>
    .el-form-item__label:lang(fa){
        float:right;
        text-align:left;
        padding:0 0 0 10px;
    }
    .el-form-item__label:lang(en){
        float: left;
        text-align: right;
        padding: 0 10px 0 0;
        white-space: nowrap;
    }
    .el-form-item__content:lang(fa){
        margin-right:100px;
        margin-left:0px;
    }
    .el-form-item__content:lang(en){
        margin-right:100px;
        margin-left:100px;
    }
    .el-form-item__error:lang(fa){
        right:0;
        left:auto;
    }
    .el-table .cell:lang(fa){
        float: right;
        text-align: right;
        direction: rtl;
    }
    .el-table .cell:lang(en){
        float: left;
        text-align: left;
        direction: ltr;
    }
    .el-popper:lang(en){
        text-align:left;
    }
    .el-popper:lang(fa){
        text-align:right;
    }
    .el-message-box__header:lang(fa)
    {
        direction:rtl;
    }
    .el-pagination{
        text-align: center;
    }
</style>
