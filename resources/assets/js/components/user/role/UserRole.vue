<template>
    <div class="container" @keydown.alt.67="createUserRole">
        <div class="row justify-content-center mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{trans('user.roleCardTitle')}}</h3>
                <div class="card-tools">
				<el-button type="success"
                  v-focus
				  size="mini"
				  @click="createUserRole">{{trans('app.addBtnLbl')}} <i class="fas fa-plus fa-fw"></i></el-button>
                </div>                  
                </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
				<el-table
					:data="list.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase())|| data.description.toLowerCase().includes(search.toLowerCase()))"
          :default-sort = "{prop: 'name', order: 'descending'}"
          :empty-text = "trans('app.no_data_found')"
					style="width: 100%" @selection-change="handleSelectionChange">
                    <el-table-column
                        type="selection"
                        width="55">
                    </el-table-column>
					<el-table-column
					  :label="trans('user.roleName')"
                      sortable
					  prop="name">
					</el-table-column>
					<el-table-column
					  :label="trans('user.roleDescription')"
                      sortable
					  prop="description">
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
                        @click="editUsers(scope.row)">{{trans('app.editBtnLbl')}} <i class="fa fa-edit blue"></i></el-button>
						<el-button
						  size="mini"
						  type="danger"
						  @click="deleteRoles(scope.row)">{{trans('app.deleteBtnLbl')}} <i class="fa fa-trash red"></i></el-button>
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
    import {errorMessage} from '../../../utilities';
    export default {
        data(){
            return{
                editMod :false,
                users :{},
                userGroups:{},
                warningAlert : trans('app.warningAlert'),
                cancelAlert : trans('app.cancelAlert'),
                noticTxt : trans('app.noticTxt'),
                cancelButtonText : trans('app.cancelButtonText'),
                confirmButtonText : trans('app.confirmButtonText'),
				form: {
                    id: '',
                    active:'',
                    name: '',
                    roleDescription: '',
				},
				tableData:[],
                search: '',
                page: 1,
                list: [],
                newsType: 'story',
                infiniteId: +new Date(),
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
                axios.get("../api/auth/roles", {
                    params: {
                    page: this.page,
                    },
                }).then(({ data }) => {
                    if (data.data.length>0) {
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
            filterActive(value, row) {
                return row.active === value;
            },
            /*
            |--------------------------------------------------------------------------
            | Load Load User Method
            | Added By e.bagherzadegan
            |--------------------------------------------------------------------------
            |
            | This method Load User Role Info
            |
            */
            LoadUserRole(){
                axios.get("../api/auth/roles").then(({data})=>(this.list = data.data)).catch((error)=>{
                    let msgErr = errorMessage(error.response.data.errors);
                    this.$message({
                      title: '',
                      message: msgErr,
                      center: true,
                      type: 'error'
                    });          
                });
            },
            /*
            |--------------------------------------------------------------------------
            | Go To Create User Role Page
            |--------------------------------------------------------------------------
            |
            | This method Load Create User Role Component
            |
            */      
            createUserRole(){
              this.$router.push({ name: 'create_user_roles'});
            },
            /*
            |--------------------------------------------------------------------------
            | Go To Edit User Role Page
            |--------------------------------------------------------------------------
            |
            | This method Load Edit User Role Component
            |
            */      
            editUsers(record){
              this.$router.push({ name: 'edit_user_roles', params: { roleId: record.id } });
            },
            /*
            |--------------------------------------------------------------------------
            | Delete User Role 
            |--------------------------------------------------------------------------
            |
            | This method delete User Role info
            |
            */         
            deleteRoles(record){
                this.$confirm(this.noticTxt,this.warningAlert, {
                confirmButtonText: this.confirmButtonText,
                cancelButtonText: this.cancelButtonText,
                type: 'warning',
                center: true
                }).then((response) => {
                  axios.delete('../api/auth/roles/'+record.id)
                .then(response => {
                    Fire.$emit('AfterCrud');
                     this.$message({
                        type: 'success',
                        center: true,
                        message:response.data.message
                      });
                this.LoadUserRole();                  
                }).catch((error) => {
                    let msgErr = errorMessage(error.response.data.errors);
                    this.$message({
                        title: error.response.data.message,
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
            //this.LoadUserRole();
            Fire.$on('AfterCrud',() => {
                
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
