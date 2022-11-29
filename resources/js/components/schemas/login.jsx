import * as yup from "yup";

const passwordRules = /^.{6,}$/;

export const loginSchema = yup.object().shape({
  email: yup
    .string()
    .email("Please enter a valid email address")
    .required("يجب إدخال البريد الالكتروني"),
  password: yup
    .string()
    .min(6)
    .matches(passwordRules, { message: "Please enter a stronger password" })
    .required("يجب ادخال كلمة المرور"),
});
// const passwordRules = /^(?=.*[a-z])(?=.*[A-Z]).{5,}$/;
