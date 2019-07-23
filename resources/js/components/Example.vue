<template>
<el-container style="height: 500px; border: 1px solid #eee;direction:rtl">
  <el-aside width="auto" style="background-color: rgb(238, 241, 246);direction:rtl;text-align:justify;padding-left:5px;">
    <el-menu default-active="2" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" :collapse="isCollapse">
	  <el-submenu index="1">
		<template slot="title">
		  <i class="el-icon-location"></i>
		  <span slot="title">Navigator One</span>
		</template>
		<el-menu-item-group>
		  <span slot="title">Group One</span>
		  <el-menu-item index="1-1">item one</el-menu-item>
		  <el-menu-item index="1-2">item two</el-menu-item>
		</el-menu-item-group>
		<el-menu-item-group title="Group Two">
		  <el-menu-item index="1-3">item three</el-menu-item>
		</el-menu-item-group>
		<el-submenu index="1-4">
		  <span slot="title">item four</span>
		  <el-menu-item index="1-4-1">item one</el-menu-item>
		</el-submenu>
	  </el-submenu>
	  <el-menu-item index="2">
		<i class="el-icon-menu"></i>
		<span slot="title">Navigator Two</span>
	  </el-menu-item>
	  <el-menu-item index="3" disabled>
		<i class="el-icon-document"></i>
		<span slot="title">Navigator Three</span>
	  </el-menu-item>
	  <el-menu-item index="4">
		<i class="el-icon-setting"></i>
		<span slot="title">Navigator Four</span>
	  </el-menu-item>
	</el-menu>
  </el-aside>
  <el-container>
    <el-header style="text-align: right; font-size: 12px">
        <i class="el-icon-setting" style="margin-right: 15px" @click="collapse()"></i>
      <span>Tom</span>
    </el-header>
    
    <el-main>
      <el-table :data="tableData">
        <el-table-column prop="date" label="Date" width="140">
        </el-table-column>
        <el-table-column prop="name" label="Name" width="120">
        </el-table-column>
        <el-table-column prop="address" label="Address">
        </el-table-column>
      </el-table>
    </el-main>
  </el-container>
</el-container>
</template>
<style>
  .el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 400px;
  }
  /* changed by e.bagherzadegan */
  .el-menu{
	direction:rtl;
	padding-left:auto !important;
	padding-right:0px;
  }
  .el-submenu__title{
	padding: 0 20px !important;

  }
  .el-submenu__icon-arrow{
	right:auto !important;
	left:20px;
  }
  .el-menu-item-group__title{
	padding:7px 45px 7px 0 !important;
  }
  .el-menu--collapse{
	float:right;
  }
</style>

<script>
  export default {
    data() {
      const item = {
        date: '2016-05-02',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles'
      };
      return {
        tableData: Array(20).fill(item),
		    isCollapse: true
      }
    },
	methods: {
        handleOpen(key, keyPath) {
        console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
        console.log(key, keyPath);
      },
	  collapse(){
		if(this.isCollapse)
			this.isCollapse = false;
		else
			this.isCollapse = true;
	  }
    }
  };
</script>
