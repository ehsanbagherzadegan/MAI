<template>
    <div class="container" @keydown.esc="backToPermissionList">
        <div class="row justify-content-center mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{trans('user.addPermissionBtnLbl')}}</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
	              <el-form @submit.native.prevent @keyup.enter.native="createUserPermission" :model="form" ref="form" label-width="130px" class="demo-ruleForm mt-3" >
                <el-form-item
                :label="trans('user.permissionName')"
                prop="name"
                :rules="[
                  { required: true, message: trans('user.permissionNameRequierdError')}
                ]"
                >
                <el-input ref="name" name="name" type="text" v-model="form.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item
                :label="trans('user.permissionDescription')"
                prop="description"
                :rules="[
                  { required: true, message: trans('user.permissionDescriptionRequierdError')}
                ]"
                >
                <el-input name="description" type="text" v-model="form.description" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item>
                  <el-button  size="mini" type="success" @click="createUserPermission()" plain>{{trans('app.submitBtnLbl')}} <i class="fas fa-check fa-fw"></i></el-button>
                  <el-button  size="mini" type="primary" @click="createContinueUserPermission()" plain>{{trans('app.submitContinueBtnLbl')}} <i class="fas fa-check-double"></i></el-button>
                  <el-button  size="mini" type="info" @click="backToPermissionList" plain>{{trans('app.backBtnLbl')}} </el-button>   
                </el-form-item>
              </el-form>
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
  export default 
    {
      data(){
          return{
              warningAlert : trans('app.warningAlert'),
              failedAlert : trans('app.failedAlert'),
              cancelAlert : trans('app.cancelAlert'),
              noticTxt : trans('app.noticTxt'),
              cancelButtonText : trans('app.cancelButtonText'),
              confirmButtonText : trans('app.confirmButtonText'),
              form: 
              {
                name: '',
                description: '',
              },
          }
      },
      methods :{
          /*
          |--------------------------------------------------------------------------
          | Back to Permission List
          |--------------------------------------------------------------------------
          |
          | This method go back to Permission list
          |
          */           
          backToPermissionList(){
            this.$router.push({ name: 'user_permissions'});
          },
          /*
          |--------------------------------------------------------------------------
          | Create Permission Method
          |--------------------------------------------------------------------------
          |
          | This method Add Permission Info To Database
          |
          */
          createUserPermission() {
            this.$refs['form'].validate((valid) => {
                if (valid) 
                  {
                    let newPermission ={
                    name: this.form.name,
                    description: this.form.description,
                    display_name: this.form.description
                  }
                  axios.post('../api/auth/permissions',newPermission).then((response) =>{
                  Fire.$emit('AfterCrud');
                  this.$message({
                    title: '',
                    message: response.data.message,
                    center: true,
                    type: 'success'
                  });
                  this.backToPermissionList();			                  
                })
                .catch((error) => {
                    let msgErr = errorMessage(error.response.data.errors);
                    this.$message({
                      title: error.response.data.message,
                      message:msgErr,
                      center: true,
                      type: 'error',
                      dangerouslyUseHTMLString: true
                    });
                });
              }
              else {
                return false;
              }
            });
            
          },
          /*
          |--------------------------------------------------------------------------
          | Create Permission Method
          |--------------------------------------------------------------------------
          |
          | This method Add Permission Info To Database
          |
          */
          createContinueUserPermission() {
            this.$refs['form'].validate((valid) => {
              if (valid) 
              {
                let newPermission ={
                name: this.form.name,
                description: this.form.description,
                display_name: this.form.description
              }
                  axios.post('../api/auth/permissions',newPermission).then((response) =>{
                  Fire.$emit('AfterCrud');
                  this.$message({
                    title: '',
                    message: response.data.message,
                    center: true,
                    type: 'success'
                  });
                  this.resetForm('form');
                })
                .catch((error) => {
                    let msgErr = errorMessage(error.response.data.errors);
                    this.$message({
                      title: error.response.data.message,
                      message:msgErr,
                      center: true,
                      dangerouslyUseHTMLString: true,
                      type: 'error'
                    });
                });
              }
              else {
                return false;
              }
            });            
          },
        /*
        |--------------------------------------------------------------------------
        | Reset Form Method
        |--------------------------------------------------------------------------
        |
        | This method Rest Form After Create User Role
        |
        */          
        resetForm(formName) {
          this.$refs[formName].resetFields();
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
      mounted() {
        this.$refs.name.focus();
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
  white-space: nowrap !important;
}
.el-form-item__label:lang(en){
  float: left;
  text-align: right;
  padding: 0 10px 0 0;
  white-space: nowrap;
}
.el-form-item__content:lang(fa){
	margin-right:160px !important;
	margin-left:0px;
  text-align: right;
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
</style>
