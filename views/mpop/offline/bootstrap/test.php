<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
<div id="example"></div>
<script>
const container = document.querySelector('#example');

const hot = new Handsontable(container, {
  data: [
    ['', 'Tesla', 'Volvo', 'Toyota', 'Ford'],
    ['2019', 10, 11, 12, 13],
    ['2020', 20, 11, 14, 13],
    ['2021', 30, 15, 12, 13]
  ],
  rowHeaders: true,
  colHeaders: true,
  height: 'auto',
  licenseKey: 'non-commercial-and-evaluation' // for non-commercial use only
});
</script>
