var citis = document.getElementById("city");
var districts = document.getElementById("district");
var wards = document.getElementById("ward");
var Parameter = {
  url: "https://raw.githubusercontent.com/daohoangson/dvhcvn/master/data/dvhcvn.json", //Đường dẫn đến file chứa dữ liệu hoặc api do backend cung cấp
  method: "GET", //do backend cung cấp
  responseType: "application/json", //kiểu Dữ liệu trả về do backend cung cấp
};
//gọi ajax = axios => nó trả về cho chúng ta là một promise
var promise = axios(Parameter);
//Xử lý khi request thành công
promise.then(function (result) {
  renderCity(result.data.data);
});

function renderCity(data) {
  for (const x of data) {
    // citis.options[citis.options.length] = new Option(x.name, x.level1_id);
    (citis.options[citis.options.length] = new Option(x.name, x.name)).setAttribute('data-id', x.level1_id);
  }

  // xứ lý khi thay đổi tỉnh thành thì sẽ hiển thị ra quận huyện thuộc tỉnh thành đó
  citis.onchange = function () {
    district.length = 1;
    ward.length = 1;
    selected = this.options[this.selectedIndex];
    id_selected = selected.dataset.id;
    if(id_selected != ""){
      const result = data.filter(n => n.level1_id === id_selected);

      for (const k of result[0].level2s) {
        (district.options[district.options.length] = new Option(k.name, k.name)).setAttribute('data-id', k.level2_id);
      }
    }
  };

   // xứ lý khi thay đổi quận huyện thì sẽ hiển thị ra phường xã thuộc quận huyện đó
  district.onchange = function () {
    ward.length = 1;

    city = citis.options[citis.selectedIndex];
    city_id = city.dataset.id;
    const dataCity = data.filter((n) => n.level1_id === city_id);

    selected = this.options[this.selectedIndex];
    id_selected = selected.dataset.id;

    if (this.value != "") {
      const dataWards = dataCity[0].level2s.filter(n => n.level2_id === id_selected)[0].level3s;

      for (const w of dataWards) {
        (wards.options[wards.options.length] = new Option(w.name, w.name)).setAttribute('data-id', w.level3_id);
      }
    }
  };
}