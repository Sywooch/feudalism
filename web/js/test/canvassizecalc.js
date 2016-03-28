for (var f = 10; f < 30; f++) {
    for (var w = 10; w < 30; w++) {
        for (var h = 5; h < 20; h++) {
            var display = new ROT.Display({fontSize: f, width: w, height: h});
            if (display.getContainer().width == display.getContainer().height) {
                console.log(display.getContainer().width, w, h, f);
            }
        }
    }
}