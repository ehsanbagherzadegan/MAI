<template>
    <div class="container" @keydown.alt.67="createPasswordPolicy">
        <div class="row justify-content-center mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{trans('passwordPolicy.cardTitle')}}</h3>
                <div class="card-tools">
				<el-button type="success"
                  v-focus
				  size="mini"
				  @click="createPasswordPolicy">{{trans('app.addBtnLbl')}} <i class="fas fa-plus fa-fw"></i></el-button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">                
				<el-table
					:data="list.filter(data => !search || data.name.toLowerCase().includes(search.toLowerCase())|| data.description.toLowerCase().includes(search.toLowerCase()))"
                    :default-sort = "{prop: 'name', order: 'ascending'}"
                    :empty-text = "trans('app.no_data_found')"
					style="width: 100%" @selection-change="handleSelectionChange">
                    <el-table-column
                        type="selection"
                        width="55">
                    </el-table-column>
					<el-table-column
					  :label="trans('passwordPolicy.name')"
                      sortable
					  prop="name">
					</el-table-column>
					<el-table-column
					  :label="trans('passwordPolicy.description')"
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
                        @click="editPasswordPolicy(scope.row)">{{trans('app.editBtnLbl')}} <i class="fa fa-edit blue"></i></el-button>
						<el-button
						  size="mini"
						  type="danger"
						  @click="deletePasswordPolicy(scope.row)">{{trans('app.deleteBtnLbl')}} <i class="fa fa-trash red"></i></el-button>
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
                 <!--<div class="block">
                        <el-pagination
                            background
                            layout="prev, pager, next"
                            prev-text="<"
                            next-text=">"
                            :page-size="pagination.per_page"                         
                            :total="pagination.total"
                            @current-change="loadPage"
                            :current-page.sync="pagination.current_page">
                        </el-pagination>             
                  </div>-->
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
        data()
        {
            return{
                cancelAlert : trans('app.cancelAlert'),
                noticTxt : trans('app.noticTxt'),
                cancelButtonText : trans('app.cancelButtonText'),
                confirmButtonText : trans('app.confirmButtonText'),
                warningAlert : trans('app.warningAlert'),
                editMod :false,
                passwordPolicys :{},
                passwordPolicyGroups:{},
                form: 
                {
                    id: '',
                    name: '',
                    description: '',
                    structure:'',
				},
				tableData:[],
                search: '',
                page: 1,
                pagination:{},
                list: [],
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
            | This method Is For Lazy Load passwordPolicy Info
            |
            */                
            infiniteHandler($state) {
                axios.get("../api/policy/password/", {
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
            | This method handle Selection PasswordPolicy Change
            |
            */              
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            /*
            |--------------------------------------------------------------------------
            | Load PasswordPolicy Method
            | Added By e.bagherzadegan
            |--------------------------------------------------------------------------
            |
            | This method Load PasswordPolicy Info
            |
            */
            LoadPasswordPolicy(){
                axios.get("../api/policy/password/").then(({data})=>(this.list = data.data)).catch((error)=>{
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
            | Go To Create PasswordPolicy Page
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Load Create profile Component
            |
            */      
            createPasswordPolicy(){
              this.$router.push({ name: 'create_password_policies'});
            },
            /*
            |--------------------------------------------------------------------------
            | Go To Edit PasswordPolicy Page
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method Load Edit profile Component
            |
            */      
            editPasswordPolicy(record){
              this.$router.push({ name: 'edit_password_policies', params: { passPolicyId: record.id } });
            },
            /*
            |--------------------------------------------------------------------------
            | Delete PasswordPolicy 
            | Added By e.bagherzadegan            
            |--------------------------------------------------------------------------
            |
            | This method delete profile info
            |
            */         
            deletePasswordPolicy(record){
				  this.$confirm(this.noticTxt,this.warningAlert, {
                  confirmButtonText: this.confirmButtonText,
                  cancelButtonText: this.cancelButtonText,
                  type: 'warning',
                  center: true
                }).then((response) => {
                  axios.delete('../api/policy/password/'+record.id)
                .then(response => {
                    Fire.$emit('AfterCrud');
                     this.$message({
                        type: 'success',
                        center: true,
                        message: response.data.message
                      });
                      this.LoadPasswordPolicy();
                }).catch((error) => {
                     let msgErr = errorMessage(error.response.data.errors);
                     this.$message({
                        title: error.response.data.message,
                        type: 'error',
                        center: true,
                        message: msgErr,
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
            //this.loadPage();
            Fire.$on('AfterCrud',() => {
                //
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
    .el-checkbox{
        top:6px;
    }
</style>
