<template>
  <div class="container" @keydown.esc="backToPermissionList">
    <div class="row justify-content-center mt-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{trans('user.lblUpdatePermissionCardTitle')}}</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <el-form  :model="form" @keyup.enter.native="updateUserPermission" ref="form" label-width="130px" class="demo-ruleForm mt-3" >
            <el-form-item
            :label="trans('user.permissionName')"
            prop="name"
            :rules="[
              { required: true, message: trans('user.permissionNameRequierdError')}
            ]"
            >
            <el-input name="name" ref="name" type="text" :disabled="true" v-model.number="form.name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item
            :label="trans('user.permissionDescription')"
            prop="description"
            :rules="[
              { required: true, message: trans('user.permissionDescriptionRequierdError')}
            ]"
            >
            <el-input name="description" type="text" ref="description" v-model="form.description" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item>
              <el-button  size="mini" type="success" @click="submitForm('form')" plain>{{trans('app.submitBtnLbl')}} <i class="fas fa-check fa-fw"></i></el-button>
              <el-button size="mini" type="info" @click="backToPermissionList" plain>{{trans('app.backBtnLbl')}} <i class="fas fa-undo"></i></el-button>
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
            form: 
            {
              id: '',
              name: '',
              description: '',          
            },
        }
    },
    methods :{
        /*
        |--------------------------------------------------------------------------
        | Load Selected Permission Info
        | Added By e.bagherzadegan
        |--------------------------------------------------------------------------
        |
        | This method load Permission info for edit
        |
        */
        loadUserPermission(){
          this.form.id=this.$route.params.permissionId;
          axios.get("../api/auth/permissions/"+this.form.id).then(({data})=>(this.form = data.data)).catch((error)=>{
              let msgErr = errorMessage(error.response.data.errors);
              this.$message({
                title: '',
                message:msgErr,
                center: true,
                dangerouslyUseHTMLString: true,
                type: 'error'
              });
              this.$router.push({name: 'edit_user_permissions'});                 
          });
        },
        /*
        |--------------------------------------------------------------------------
        | Back to Permission List
        | Added By e.bagherzadegan        
        |--------------------------------------------------------------------------
        |
        | This method go back to Permissions list
        |
        */
        
        backToPermissionList(){
          this.$router.push({ name: 'user_permissions'});
        },
        /*
        |--------------------------------------------------------------------------
        | Update Permission Method
        | Added By e.bagherzadegan        
        |--------------------------------------------------------------------------
        |
        | This method Update Permission Info To Database
        |
        */          
        updateUserPermission(){
          axios.put('../api/auth/permissions/'+this.form.id,{name: this.form.name,
          description: this.form.description}).then(response => {
          this.$message({
            type: 'success',
            center: true,
            message:response.data.message
          });          
            }).catch((error) => {
              let msgErr = errorMessage(error.response.data.errors);           
              this.$message({
                title: error.response.data.message,
                type: 'error',
                dangerouslyUseHTMLString: true,
                center: true,
                message:msgErr
              });
          }); 
        },
        /*
        |--------------------------------------------------------------------------
        | Submit Form Method
        | Added By e.bagherzadegan        
        |--------------------------------------------------------------------------
        |
        | This method Submit Form
        |
        */             
        submitForm(formName) {
          this.$refs[formName].validate((valid) => {
            if (valid) 
            {
              this.updateUserPermission();
              this.backToPermissionList();
            }
            else {
              return false;
            }
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
        this.loadUserPermission();        
        Fire.$on('AfterCrud',() => {
            //
        });
    },
    mounted(){
      this.$refs.description.focus();
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
