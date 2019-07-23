<template>
  <el-container>
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
</template>
<style>

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
        tableData: Array(4).fill(item),
      }
    },
	methods: {
	  
	  }
    }
</script>
