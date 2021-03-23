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
            "!vendor/**",
            "!src/**",
            "!packaged/**",
            "!gulpfile.babel.js",
            "!webpack.config.js",
            "!readme.txt",
            "!package.json",
            "!package-lock.json",
            "!composer.json",
            "!composer.lock",
            "!phpcs.xml.dist"
        ])
        .pipe(replace("zthemename", info.name))
        .pipe(replace("zdescription", info.description))
        .pipe(replace("zversion", info.version))
        .pipe(
            rename((path) => {
                path.dirname = `${info.name}/` + path.dirname;
            })
        )
        .pipe(zip(`${info.name}.zip`))
        .pipe(gulp.dest("packaged"));
};

export default compress;
