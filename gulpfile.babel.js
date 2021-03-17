import gulp from "gulp";
import zip from "gulp-zip";
import rename from "gulp-rename";
import replace from "gulp-replace";
import info from "./package.json";
import del from "del";

export const compress = () => {
    del("packaged");
    return gulp
        .src([
            "**/*",
            "!node_modules/**",
            "!src/**",
            "!packaged/**",
            "!gulpfile.babel.js",
            "!webpack.config.js",
            "!readme.txt",
            "!package.json",
            "!package-lock.json"
        ])
        .pipe(replace("zthemename", info.name))
        .pipe(
            rename((path) => {
                path.dirname = `${info.name}/` + path.dirname;
            })
        )
        .pipe(zip(`${info.name}.zip`))
        .pipe(gulp.dest("packaged"));
};

export default compress;
